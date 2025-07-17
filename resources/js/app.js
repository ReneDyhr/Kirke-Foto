window.SelectBind = function () {
    console.log('REBIND');
    NiceSelect.bind(document.getElementById("sogn"), { searchable: true, placeholder: 'Vælg sogn', searchtext: 'Søg', selectedtext: 'geselecteerd' });

    NiceSelect.bind(document.getElementById("provsti"), { searchable: true, placeholder: 'Vælg provsti', searchtext: 'Søg', selectedtext: 'geselecteerd' });

    NiceSelect.bind(document.getElementById("stift"), { searchable: true, placeholder: 'Vælg stift', searchtext: 'Søg', selectedtext: 'geselecteerd' });

    NiceSelect.bind(document.getElementById("kirke"), { searchable: true, placeholder: 'Vælg kirke', searchtext: 'Søg', selectedtext: 'geselecteerd' });
}

window.SelectBind();
