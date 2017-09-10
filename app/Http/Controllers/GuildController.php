<?php

namespace App\Http\Controllers;

use App\Guild;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class GuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('guild.list', ['guilds' => Guild::orderBy('name', 'asc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'url' => 'required',
        ]);
		
		$guild = new Guild;
		$guild->name = $request->name;
		$guild->url = $request->url;
		$guild->webhook = $request->webhook;
		$guild->save();

        return redirect('/guild');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('guild.members', ['guild' => Guild::find($id)]);
    }

    public function members($id) {
        return response()->json(Guild::find($id)->members->toArray());
    }
	
    public function characters($id) {
        return response()->json(Guild::find($id)->characters()->with('member')->get()->toArray());
    }
    
    public function scrapeGuild(Request $request) {
    	
        $proc = new Process('node resources/scripts/guild.js ' . $this->argument('guild'));
        $name;
        $image;
        $status = $proc->run(
			function ($type, $data) use (&$name, &$image) {
				if ($type == Process::OUT) {
					list($name, $image) = explode(':|:', $data);
				} else {
					$this->error($data);
				}
			}
        );
        
		if ($status) {
			return response("Failed to scrape guild.", 400);
        }
        
        return response()->json(['name' => $name, 'image' => $image]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Guild::find($id)->delete();
        
        return redirect('/guild');
    }
}