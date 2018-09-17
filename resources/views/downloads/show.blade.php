<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Detail {!! $download->id !!}</title>
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Detail stiahnutia</h4>
            </div>            <!-- /modal-header -->
            <div class="modal-body">


				<div class="table-responsive">
	                <table class="table">
	                    <thead>
							<tr>
								<td>identifikátor:</td>
								<td>{!! $download->id !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>typ:</td>
								<td>{!! $download->type !!}</td>
							</tr>
                            <tr>
                                <td>Název organizace:</td>
                                <td>{!! $download->company !!}</td>
                            </tr>
                            <tr>
                                <td>Adresa:</td>
                                <td>{!! $download->address !!}</td>
                            </tr>
                            <tr>
                                <td>Země:</td>
                                <td>{!! $download->country !!}</td>
                            </tr>
                            <tr>
                                <td>Kontaktní osoba:</td>
                                <td>{!! $download->contact_person !!}</td>
                            </tr>
                            <tr>
                                <td>E-mail:</td>
                                <td>{!! $download->email !!}</td>
                            </tr>
                            <tr>
                                <td>Telefón:</td>
                                <td>{!! $download->phone !!}</td>
                            </tr>
                            <tr>
                                <td>Název publikace:</td>
                                <td>{!! $download->publication_name !!}</td>
                            </tr>
                            <tr>
                                <td>Jméno autora:</td>
                                <td>{!! $download->publication_author !!}</td>
                            </tr>
                            <tr>
                                <td>Rok vydání:</td>
                                <td>{!! $download->publication_year !!}</td>
                            </tr>
                            <tr>
                                <td>Počet výtisků:</td>
                                <td>{!! $download->publication_print_run !!}</td>
                            </tr>
							<tr>
								<td>Poznámka:</td>
								<td>{!! $download->note !!}</td>
							</tr>
							<tr>
								<td><img src="{!! $download->item->getImagePath(); !!}" alt="náhľad" class="img-responsive" ></td>
								<td>
									<a href="{!! URL::to('item/' . $download->item->id . '/edit' ) !!}">{!! $download->item->author !!} - {!! $download->item->title !!}</a>
								</td>
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