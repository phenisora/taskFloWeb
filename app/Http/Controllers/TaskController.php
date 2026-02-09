<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // On récupère uniquement les tâches de l'utilisateur connecté
        $tasks = auth()->user()->tasks()->latest()->get();
    
        // On envoie les tâches à la vue 'dashboard'
        return view('dashboard', compact('tasks'));
    }


    public function store(Request $request) 
    {
        
        $request->validate(['title' => 'required|string|max:255']);

        // 2. Création liée à l'utilisateur connecté
        $request->user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back(); // On recharge la page
    }

 // N'oublie pas : use App\Models\Task; en haut

public function update(Request $request, Task $task)
{
    // 1. On vérifie si l'utilisateur a le droit de modifier cette tâche
    if ($task->user_id !== auth()->id()) {
        abort(403);
    }

    // 2. Si la requête contient un titre, c'est une modification complète
    if ($request->has('title')) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $task->update($validated);
    } 
    // 3. Sinon, c'est juste le bouton "Terminer/Annuler" (Toggle)
    else {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);
    }

    return back()->with('status', 'task-updated');
}


}
