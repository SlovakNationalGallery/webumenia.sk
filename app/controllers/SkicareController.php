<?php

class SkicareController extends \BaseController {

	public function index()
	{
		$sketchbooks = Sketchbook::orderBy('order', 'asc')->get();
		$max_height = Sketchbook::max('height');
		// dd($max_height);
		return View::make('skicare', ['sketchbooks' => $sketchbooks , 'max_height'=>$max_height]);
	}

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
		$sketchbook = Sketchbook::find($id);
		$item = $sketchbook->item;

		if (empty($item->iipimg_url)) {
			App::abort(404);
		}

		// $is_globaly_horizontal = (Image::make($item->getImagePath(true))->height()==800) ? true : false;
		$is_globaly_horizontal = true;
		$trans = array(":" => "_", " / " => "_", " " => "_");
	    $folder_name = $item->id . ' ' . $sketchbook->title; 
	    $folder_name = strtr($folder_name, $trans);

		$path = storage_path() . '/skicare/' . $folder_name;
		if(!File::exists($path)) {
		    File::makeDirectory($path);
		}
		// dd($path);

		$pages = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get();
		$images = [];
		$pocet_stran = count($pages);
		foreach ($pages as $i=>$item) {
			if (App::runningInConsole()) {
				echo date('h:i:s'). " strana " . ($i+1) ."/" . $pocet_stran . "\n";
			}
			$is_horizontal = (Image::make($item->getImagePath(true))->width()==800) ? true : false;
			$rot = ($is_globaly_horizontal != $is_horizontal) ? '&ROT=90' : '';
			$image_url = 'http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi?FIF=' . $item->iipimg_url . $rot . '&CVT=JPG';
			$images[] = $image_url;
			$img = Image::make($image_url)->save($path . '/' . sprintf("%02d", $i+1) . '.jpg');
			$img->destroy();
		}


		if (App::runningInConsole()) {
			echo "*** Generuje sa PDF z {$pocet_stran} obrázkov. *** \n";
		}

		$out=array();
		$err = 0;
		$pdf_name = strtr($sketchbook->title, $trans) . '.pdf';
		$run = exec('convert  '.$path.'/* '.$path. '/' . $pdf_name,$out,$err);
		echo implode ("\n",$out);

		$sketchbook->generated_at = date("Y-m-d H:i:s");
		$sketchbook->file = $pdf_name;
		$sketchbook->save();


		if (App::runningInConsole()) {
			return true;
		}
		// return View::make('skicar', array('item'=>$item));		
	}

}