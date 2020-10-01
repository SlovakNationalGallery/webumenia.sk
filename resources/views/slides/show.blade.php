<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {!! $slide->id !!}</title>  
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail slajdu</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>ID:</td>
								<td>{!! $slide->id !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Nadpis:</td>
								<td>{!! $slide->title !!}</td>
							</tr>
							<tr>
								<td>Podnadpis:</td>
								<td>{!! $slide->subtitle !!}</td>
							</tr>
							<tr>
								<td>Url:</td>
								<td><a href="{!! $slide->url !!}">{!! $slide->url !!}</a></td>
							</tr>
							<tr>
								<td>#&nbsp;kliknutí</td>
								<td>{!! $slide->click_count !!}</td>
							<tr>
								<td>Obrázok</td>
								<td>
									<img src="{!! $slide->header_image_src !!}" 
									srcset="{!! $slide->header_image_srcset !!}" 
									alt="náhľad" class="img-responsive"
									style="width:100%;height:270px;object-fit:cover"
									onerror="this.onerror=null;this.srcset=''"
									/></td>
							</tr>
	                    </tbody>
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
				<a href="{!! URL::to('slide/' . $slide->id . '/edit' ) !!}"><button type="button" class="btn btn-default">Upraviť</button></a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>