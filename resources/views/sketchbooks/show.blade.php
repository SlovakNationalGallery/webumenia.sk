<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {!! $collection->id !!}</title>  
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail kolekcie</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>identifikátor:</td>
								<td>{!! $collection->id !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>typ:</td>
								<td>{!! $collection->type !!}</td>
							</tr>
							<tr>
								<td>názov:</td>
								<td>{!! $collection->name !!}</td>
							</tr>
							<tr>
								<td>text:</td>
								<td>{!! $collection->text !!}</td>
							</tr>
							@foreach ($collection->items as $item)
							<tr>
								<td><img src="{!! $item->getImagePath(); !!}" alt="náhľad" class="img-responsive" ></td>
								<td>
									<a href="{!! URL::to('item/' . $item->id . '/edit' ) !!}">{!! $item->author !!} - {!! $item->title !!}</a>
								</td>
							</tr>
							@endforeach
	                    </tbody>
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>