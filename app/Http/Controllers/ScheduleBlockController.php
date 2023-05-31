<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleBlock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ScheduleBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name'] = "Schedule";
        $data['page_info'] = "Block Calendar";
        $data['card_title'] = "Block Calendar";

        return view('schedule_blocks.index', $data);
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

        $validated = $request->validate([
            'block_reason'  => 'required|max:255',
            'block_date'    => 'required|date',
            'block_start'   => 'required',
            'block_end'     => 'required'
        ]);

        $block = new ScheduleBlock();
        $block->description = $validated['block_reason'];
        $block->date = Carbon::parse($validated['block_date']);
        $block->start_time = $validated['block_start'];
        $block->end_time = $validated['block_end'];
        $block->location_id = session('current_location')->id;
        $block->schedule_type_id = 9;

        if($block->save())
        {
            return redirect()->route('schedule.index')->with('success', 'Calendar block created.');
        }

        return back()->with('error', 'Oops! Something went wrong.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['block'] = ScheduleBlock::findOrFail($id);

        $data['block']['date'] = Carbon::parse($data['block']['date'])->format('m/d/Y');
        $data['page_name'] = "Schedule";
        $data['page_info'] = "Block Calendar";
        $data['card_title'] = "Block Calendar";

        return view('schedule_blocks.edit', $data);
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
        $validated = $request->validate([
            'block_reason'  => 'required|max:255',
            'block_date'    => 'required|date',
            'block_start'   => 'required',
            'block_end'     => 'required'
        ]);

        $block = ScheduleBlock::findOrFail($id);
        $block->description = $validated['block_reason'];
        $block->date = Carbon::parse($validated['block_date']);
        $block->start_time = $validated['block_start'];
        $block->end_time = $validated['block_end'];
        $block->location_id = session('current_location')->id;
        $block->schedule_type_id = 9;

        if($block->update())
        {
            return redirect()->route('schedule.index')->with('success', 'Calendar block updated.');
        }

        return back()->with('error', 'Oops! Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $block = ScheduleBlock::findOrFail($id);
        $block->delete();
        return redirect()->route('schedule.index')->with('success', 'Calendar block removed.');
    }
}

