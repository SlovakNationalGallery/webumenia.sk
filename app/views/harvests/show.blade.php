<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {{ $harvest->id }}</title>  
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail harvestu</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>identifikátor:</td>
								<td>{{ $harvest->id }}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>base_url:</td>
								<td>{{ $harvest->base_url }}</td>
							</tr>
							<tr>
								<td>metadata prefix:</td>
								<td>{{ $harvest->metadata_prefix }}</td>
							</tr>
							<tr>
								<td>set_name:</td>
								<td>{{ $harvest->set_name }}</td>
							</tr>
							<tr>
								<td>Špecifikácia setu:</td>
								<td>{{ $harvest->set_spec }}</td>
							</tr>
							<tr>
								<td>Popis setu:</td>
								<td>{{ $harvest->set_description }}</td>
							</tr>
							<tr>
								<td>Kolekcia:</td>
								<td>{{ (count($harvest->collection)) ? $harvest->collection->name : 'žiadna' }}</td>
							</tr>
							<tr>
								<td>Harvest vytvorený:</td>
								<td>{{ $harvest->created_at }}</td>
							</tr>
							<tr>
								<td>Naposledy spustený:</td>
								<td>{{ $harvest->initiated }}</td>
							</tr>							
							<tr>
								<td>Naposledy skompletizovaný:</td>
								<td>{{ $harvest->completed }}</td>
							</tr>							
							<tr>
								<td>Status:</td>
								<td>{{ $harvest->status }}</td>
							</tr>							
							<tr>
								<td>Status správa:</td>
								<td>{{ nl2br($harvest->status_messages) }}</td>
							</tr>							
	                    </tbody>
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>