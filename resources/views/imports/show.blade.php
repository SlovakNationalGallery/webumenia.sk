<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {!! $import->name !!}</title>  
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail importu</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>identifikátor:</td>
								<td>{!! $import->id !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>name:</td>
								<td>{!! $import->name !!}</td>
							</tr>
							<tr>
								<td>settings:</td>
								<td>{!! $import->settings !!}</td>
							</tr>
							<tr>
								<td>mapping:</td>
								<td>{!! $import->mapping !!}</td>
							</tr>
							<tr>
								<td>Vytvorený:</td>
								<td>{!! $import->created_at !!}</td>
							</tr>
							<tr>
								<td>Naposledy spustený:</td>
								<td>{!! $import->initiated !!}</td>
							</tr>
							{{--  
							<tr>
								<td>Naposledy skompletizovaný:</td>
								<td>{!! $import->completed !!}</td>
							</tr>
							<tr>
								<td>Status:</td>
								<td>{!! $import->status !!}</td>
							</tr>							
							<tr>
								<td>Status správa:</td>
								<td>{!! nl2br($import->status_messages) !!}</td>
							</tr>							
							--}}							
	                    </tbody>
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>