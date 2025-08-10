<div class="content">
    <div class="sidebar-main">
        <h1>Kontakt</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form wire:submit="send">
            <div class="form-group">
                <label>Navn *</label>
                <input type="text" required wire:model="name" class="form-control">
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="text" required wire:model="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Emne *</label>
                <input type="text" required wire:model="subject" class="form-control">
            </div>
            <div class="form-group">
                <label>Besked *</label>
                <textarea class="form-control" required wire:model="message" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>Sikkerhedstjek *</label>
                <p>Skriv resultatet i feltet herunder: {{ $mathNumber1 }} + {{ $mathNumber2 }}</p>
                <input type="number" required wire:model="mathResult" class="form-control">
            </div>
            <p>* Disse felter skal udfyldes</p>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
    <div class="sidebar">
        <div class="card no-padding">
            <div class="card-body">
                <div class="card-text"><img src="/images/me_2.jpg" alt="René Dyhr" class="image about-us-image"></div>
            </div>
        </div>
        <div class="card ">
            <div class="card-body">
                <h5 class="card-title">Adresse &amp; Telefon Nr.</h5>
                <div class="card-text">
                    <p style="margin-bottom: 5px;">René Dyhr</p>
                    <p style="margin-bottom: 5px;">Violvej 5</p>
                    <p style="margin-bottom: 5px;">6670 Holsted, Danmark</p>
                    <p style="margin-bottom: 5px;">+45 27 84 78 08</p>
                </div>
            </div>
        </div>
    </div>
</div>
