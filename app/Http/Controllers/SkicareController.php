<?php

use Conner\Tagging\TaggingUtil;

class SkicareController extends \BaseController
{

    public function index()
    {
        $sketchbooks = Sketchbook::orderBy('order', 'asc')->get();
        $max_height = Sketchbook::max('height');
        return view('skicare', ['sketchbooks' => $sketchbooks , 'max_height'=>$max_height]);
    }

    public function getList()
    {
        $sketchbooks = Sketchbook::published()->orderBy('order', 'asc')->with('Item')->get();

        $sketchbooks_list = [];
        foreach ($sketchbooks as $sketchbook) {
            $sketchbooks_list[] = [
                'item_id' => $sketchbook->item_id,
                'title' => $sketchbook->title,
                'width' => $sketchbook->width,
                'height' => $sketchbook->height,
                'file' => $sketchbook->file, //$sketchbook->file,
                'preview' => asset($sketchbook->item->getImagePath()),
            ];
        }

        return Response::json($sketchbooks_list);
    }

    public function getZoom($id)
    {
        $item = Item::find($id);

        if (empty($item->iipimg_url)) {
            App::abort(404);
        }

        $related_items = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get();

        return view('skicar', array('item'=>$item, 'related_items'=>$related_items));
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

        $path = $sketchbook->getPath($create = true);
        // system("rm -rf ".escapeshellarg($path.'/*')); //zmaz vsetko v priecinku

        // dd($path);

        $pages = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get();
        $images = [];
        $pocet_stran = count($pages);

        foreach ($pages as $i => $item) {
        //  // if ($i>140) {
            if (App::runningInConsole()) {
                echo date('h:i:s'). " strana " . ($i+1) ."/" . $pocet_stran . " ". $item->id . "\n";
            }
            $item->downloadImage();
            if ($item->has_image) {
                $is_horizontal = (Image::make($item->getImagePath(true))->width()==800) ? true : false;
            } else {
                $is_horizontal = (isset($is_horizontal)) ? $is_horizontal : $is_globaly_horizontal;
                echo " CHYBA " . $item->id . " chyba nahlad " . "\n";
            }
            if ($item->iipimg_url) {
                $rot = ($is_globaly_horizontal != $is_horizontal) ? '&ROT=90' : '';
                $image_url = 'http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi?FIF=' . $item->iipimg_url . '&QLT=90' . $rot . '&CVT=JPG';
                $images[] = $image_url;
                try {
                    $data = file_get_contents($image_url);
                    file_put_contents($path . '/' . sprintf("%02d", $i+1) . '.jpg', $data);
                } catch (Exception $ex) {
                    echo " CHYBA " . $item->id . " chybny ZOOM " . "\n";
                }

                // $img = Image::make($image_url)->save($path . '/' . sprintf("%02d", $i+1) . '.jpg');
                // $img->destroy();
            } else {
                echo " CHYBA " . $item->id . " chyba zoom " . "\n";
            }
        // }
        }


        if (App::runningInConsole()) {
            echo "*** Generuje sa PDF z {$pocet_stran} obrázkov. *** \n";
        }

        $out=array();
        $err = 0;
        $pdf_name = $sketchbook->id . '-' . TaggingUtil::slug($sketchbook->title) . '.pdf';

        $run = exec('convert  '.$path.'/*.jpg '.$path. '/' . $pdf_name, $out, $err);
        // echo implode ("\n",$out);

        $sketchbook->generated_at = date("Y-m-d H:i:s");
        $sketchbook->file = $pdf_name;
        $sketchbook->publish = 1;
        $sketchbook->save();


        if (App::runningInConsole()) {
            return true;
        }
        // return view('skicar', array('item'=>$item));
    }
}
