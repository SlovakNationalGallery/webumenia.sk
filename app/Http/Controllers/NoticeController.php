<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class NoticeController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        return view('notices.form')->with('notice', $notice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate(Notice::$rules);

        $notice->update(array_merge(
            $request->all(),
            ['updated_by' => auth()->user()->username]
        ));

        return redirect()
            ->route('notices.edit', $notice)
            ->with('message', 'Oznam bol upravený');
    }
}
