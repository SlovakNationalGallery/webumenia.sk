<?php

class SkicareController extends \BaseController {

	public function getZoom($id)
	{
		$item = Item::find($id);

		if (empty($item->iipimg_url)) {
			App::abort(404);
		}

		$related_items = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get();

		return View::make('skicar', array('item'=>$item, 'related_items'=>$related_items));
	}

	public function downloadAllPages($id)
	{
		$item = Item::find($id);

		if (empty($item->iipimg_url)) {
			App::abort(404);
		}

		$is_globaly_vertical = (Image::make($item->getImagePath(true))->height()==800) ? true : false;
		$name = strtolower(implode(', ', $item->authors)) . ' ' . $item->id;
		$trans = array(":" => "_", " " => "_");
	    $name = strtr($name, $trans);

		$path = storage_path() . '/skicare/' . $name;
		if(!File::exists($path)) {
		    File::makeDirectory($path);
		}
		// dd($path);

		$pages = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get();
		$images = [];
		foreach ($pages as $i=>$item) {
			$is_vertical = (Image::make($item->getImagePath(true))->height()==800) ? true : false;
			$rot = ($is_globaly_vertical != $is_vertical) ? '&ROT=90' : '';
			$image_url = 'http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi?FIF=' . $item->iipimg_url . $rot . '&CVT=JPG';
			$images[] = $image_url;
			Image::make($image_url)->save($path . '/' . sprintf("%02d", $i+1) . '.jpg');
		}

		dd($images);

		// return View::make('skicar', array('item'=>$item));		
	}

}