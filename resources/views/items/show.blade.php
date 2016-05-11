<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {!! $item->id; !!}</title>  
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail diela</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>inventárne číslo:</td>
								<td>{!! $item->identifier !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>autor:</td>
								<td>{!! $item->author !!}</td>
							</tr>
							<tr>
								<td>názov:</td>
								<td>{!! $item->title !!}</td>
							</tr>
							<tr>
								<td>popis:</td>
								<td>{!! $item->description !!}</td>
							</tr>
							<tr>
								<td>výtvarný druh:</td>
								<td>{!! $item->work_type; !!}</td>
							</tr>
							<tr>
								<td>stupeň spracovania:</td>
								<td>{!! $item->work_level; !!}</td>
							</tr>
							<tr>
								<td>žáner:</td>
								<td>{!! $item->topic; !!}</td>
							</tr>
							<tr>
								<td>tagy:</td>
								<td>{!! $item->subject; !!}</td>
							</tr>
							<tr>
								<td>miery:</td>
								<td>{!! $item->measurement; !!}</td>
							</tr>
							<tr>
								<td>datovanie:</td>
								<td>{!! $item->dating; !!}</td>
							</tr>
							<tr>
								<td>datovanie najskôr:</td>
								<td>{!! $item->date_earliest; !!}</td>
							</tr>
							<tr>
								<td>datovanie najneskôr:</td>
								<td>{!! $item->date_latest; !!}</td>
							</tr>
							<tr>
								<td>materiál:</td>
								<td>{!! $item->medium; !!}</td>
							</tr>
							<tr>
								<td>technika:</td>
								<td>{!! $item->technique; !!}</td>
							</tr>
							<tr>
								<td>značenie:</td>
								<td>{!! $item->inscription; !!}</td>
							</tr>
							<tr>
								<td>geografická oblasť:</td>
								<td>{!! $item->place; !!} <br>Latitude : {!! $item->lat !!} | Longitude : {!! $item->lng !!}</td>
							</tr>
							<tr>
								<td>stupeň spracovania:</td>
								<td>{!! $item->state_edition; !!}</td>
							</tr>
							<tr>
								<td>stupeň integrity:</td>
								<td>{!! $item->integrity; !!}</td>
							</tr>
							<tr>
								<td>integrita s dielami:</td>
								<td>{!! $item->integrity_work; !!}</td>
							</tr>
							<tr>
								<td>galéria:</td>
								<td>{!! $item->gallery; !!}</td>
							</tr>
							<tr>
								<td>url s obrázkom:</td>
								<td><a href="{!! $item->img_url; !!}" target="_blank">{!! $item->img_url; !!}</a></td>
							</tr>
							<tr>
								<td>IIPImage url:</td>
								<td><a href="{!! $item->iipimg_url; !!}" target="_blank">{!! $item->iipimg_url; !!}</a></td>
							</tr>
							<tr>
								<td>obrázok:</td>
								<td><img src="{!! $item->getImagePath(); !!}" alt="náhľad" class="img-responsive" ></td>
							</tr>
	                    </tbody>
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
				<a href="{!! $item->getUrl() !!}" class="btn btn-default">Zobraziť na webe</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>