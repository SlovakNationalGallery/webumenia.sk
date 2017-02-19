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
								<td>názov:</td>
								<td>{!! $import->name !!}</td>
							</tr>
							@if ($import->settings)
							<tr>
								<td>settings:</td>
								<td>{!! $import->settings !!}</td>
							</tr>
							@endif
							@if ($import->mapping)
							<tr>
								<td>mapping:</td>
								<td>{!! $import->mapping !!}</td>
							</tr>
							@endif
							@if ($import->dir_path)
							<tr>
								<td>priečinok:</td>
								<td>{!! $import->dir_path !!}</td>
							</tr>
							@endif
							<tr>
								<td>vytvorený:</td>
								<td>{!! ($import->created_at) ? $import->created_at->format('d.m.Y h:i') : '' !!}</td>
							</tr>
							{{--  
							<tr>
								<td>Naposledy spustený:</td>
								<td>{!! ($import->lastRecord) ? $import->initiated->format('d.m.Y h:i') : '' !!}</td>
							</tr>
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

	                <h4 class="modal-title top-space">História</h4>

	                <table class="table table-condensed top-space">
	                	<thead>
	                		<tr>
	                			<th>súbor</th>
	                			<th>status</th>
	                			<th>začiatok</th>
	                			<th>koniec</th>
	                			<th># importovaných</th>
	                		</tr>
	                	</thead>
	                	@foreach ($import->records as $record)
	                		@if ($record->status=='error')
	                			<tr class="danger">
	                		@elseif ($record->status=='in progress')
	                			<tr class="warning">
	                		@else
	                			<tr>
	                		@endif
	                			<td>{{ $record->filename }}</td>
	                			<td>{{ $record->status }}</td>
	                			<td>{{ ($record->started_at) ? $record->started_at->format('d.m.Y h:i') : '' }}</td>
	                			<td>{{ ($record->completed_at) ? $record->completed_at->format('d.m.Y h:i') : '' }}</td>
	                			<td class="text-right">{{ $record->imported_items }}</td>
	                		</tr>
	                	@endforeach
	                </table>
	            </div>


            </div>            <!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
            </div>            <!-- /modal-footer -->
</body>
</html>