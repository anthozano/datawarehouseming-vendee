<?php

namespace App\Http\Controllers;

use App\Personne;
use Illuminate\Http\Request;

class PersonneController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $personnes = Personne::take(100)->get();
        return view('personnes.index', compact('personnes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('personnes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $personne = Personne::create($request->all());
        return redirect(route('personnes.show', $personne));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $personne = Personne::find($id);
        return view('personnes.show', compact('personne'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $personne = Personne::findOrFail($id);
        return view('personnes.edit', compact('personne'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $personne = Personne::findOrFail($id);
        $personne->update($request->all());
        return redirect(route('personnes.edit', $personne));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $personne = Personne::destroy($id);
        return view('personne.destroy', compact('personne'));
    }
}
