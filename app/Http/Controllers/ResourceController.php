<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resources = $query->get(); // On récupère TOUTES les ressources pour la boucle
        return view('resources.index', compact('resources'));
    }

    public function managerIndex()
    {
        if (Auth::user()->role === 'admin') {
            // L'admin voit tout le catalogue (Point 4.2)
            $resources = Resource::with('manager')->get();
        } else {
            // Le responsable ne voit que ses ressources (Point 3.1)
            $resources = Resource::where('manager_id', Auth::id())->get();
        }

        return view('resources.manager', compact('resources'));
    }

    public function create()
    {
        $managers = User::where('role', 'responsable')->get();
        return view('resources.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'cpu' => 'required|integer',
            'ram' => 'required|integer',
            'category' => 'required|string',
        ]);

        Resource::create([
            'name' => $request->name,
            'type' => $request->type,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'category' => $request->category,
            'status' => 'disponible',
            'manager_id' => auth()->id(), // Le créateur devient le manager
        ]);

        return redirect()->route('resources.index')->with('success', 'Ressource ajoutée au parc.');
    }

    public function edit(Resource $resource)
    {
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        $managers = User::where('role', 'responsable')->get();
        return view('resources.edit', compact('resource', 'managers'));
    }

    public function update(Request $request, Resource $resource)
    {
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Ajout de la validation pour la maintenance planifiée (Point 4.4)
        $validated = $request->validate([
            'name' => 'required|string',
            'maintenance_start' => 'nullable|date',
            'maintenance_end' => 'nullable|date|after:maintenance_start',
        ]);

        $resource->update($request->all());

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'Admin: Mise à jour',
            'description' => "Modifications globales sur {$resource->name}"
        ]);

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Catalogue mis à jour.');
        }

        return redirect()->route('resources.manager')->with('success', 'Ressource mise à jour avec succès.');
    }

    public function toggleMaintenance(Resource $resource)
    {
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $newStatus = ($resource->status === 'maintenance') ? 'disponible' : 'maintenance';
        $resource->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "État de la ressource changé en {$newStatus}.");
    }
}