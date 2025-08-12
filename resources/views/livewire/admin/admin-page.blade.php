<div class="content">
    <div>
        <h1>Kirker</h1>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 20%">Navn</th>
                    <th style="width: 15%">Sogn</th>
                    <th style="width: 15%">Provsti</th>
                    <th style="width: 15%">Stift</th>
                    <th style="width: 10%">Billeder</th>
                    <th style="width: 10%">Drone</th>
                    <th style="width: 30%">Handlinger</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->churches as $church)
                    <tr>
                        <td>
                            <a href="/kirke/{{ $church->parish->url ?? '' }}/{{ $church->url }}">{{ $church->name }}</a>
                        </td>
                        <td>{{ $church->parish->name ?? 'Ikke angivet' }}</td>
                        <td>{{ $church->parish->deanery->name ?? 'Ikke angivet' }}</td>
                        <td>{{ $church->parish->deanery->diocese->name ?? 'Ikke angivet' }}</td>
                        <td>{{ $church->images->count() }}</td>
                        <td>{{ $church->drone_approval ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="" class="btn btn-default"><i class="fa fa-edit"></i></a>
                            <a href="" class="btn btn-default"><i class="fa fa-inbox" data-count="1"></i></a>
                            <a href="" class="btn btn-default"><i class="fa fa-camera"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
