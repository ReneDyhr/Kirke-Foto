<div class="content">
    <div>
        <h1 class="church-header">Gårslev Kirke</h1><span class="church-breadcrumb">Gårslev Sogn - Vejle Provsti -
            Haderslev Stift</span>
        <p><a href="https://sogn.dk/gaarslev" target="_blank" rel="noreferrer">Gå til Gårslev Sogn via sogn.dk</a></p>
        <div class="church-text">Det er muligt at få billederne i fuld opløsning, hvis man er medlem af kirkens personale
            eller menighedsråd.</div><br>
        <div class="photos">
            <div class="photo"><img alt="DSC06857.jpg"
                    src="https://kirke-foto.dk/images/thumb_eQUvKhr1pccOaJOyQ0bLmUYZ7TQOmkHkqu6TdzFR.jpg"></div>
            <div class="photo"><img alt="DSC06856.jpg"
                    src="https://kirke-foto.dk/images/thumb_EUBvLFZoDFZHYpaNLJ9TsOFJz5CiqnTOvAoEp5mY.jpg"></div>
            <div class="photo"><img alt="DSC06854.jpg"
                    src="https://kirke-foto.dk/images/thumb_Gx2Ok14Cvc7AUwstAI1vu0q3eSHYe7jNNGY8fBDj.jpg"></div>
            <div class="photo"><img alt="DSC06863.jpg"
                    src="https://kirke-foto.dk/images/thumb_nKqJQPVacU7iRj8LRxoIVEgoziXJbDTdI6pUvhE9.jpg"></div>
            <div class="photo"><img alt="DSC06862.jpg"
                    src="https://kirke-foto.dk/images/thumb_fISRbrgJDHlSa2Dwh9buzVoDjvPx56SXG3FEgKd4.jpg"></div>
            <div class="photo"><img alt="DSC06860.jpg"
                    src="https://kirke-foto.dk/images/thumb_u5Zj4dvmmEhqaZsSicUDEuUnwFcFLwR90SwgzNmT.jpg"></div>
            <div class="photo"><img alt="DSC06859.jpg"
                    src="https://kirke-foto.dk/images/thumb_Xc4d1wx8ZE1izWfiUpjWZD1zg6vhlQ0IlLPqL57Q.jpg"></div>
            <div class="photo"><img alt="DSC06858.jpg"
                    src="https://kirke-foto.dk/images/thumb_IwD72RglAS88BwMVy4B2GmFb6pWDw63M2yEYBvMx.jpg"></div>
            <div class="photo"><img alt="DSC06855.jpg"
                    src="https://kirke-foto.dk/images/thumb_QgK44VwpLjTQtES6nHmKnRyZoPtvkaz43mxL8qC9.jpg"></div>
            <div class="photo"><img alt="DSC06865.jpg"
                    src="https://kirke-foto.dk/images/thumb_k90czogC7c8vkiV4e1q1N5Po0MgUS4u8ytP8e5xh.jpg"></div>
            <div class="photo"><img alt="DJI_0476.jpg"
                    src="https://kirke-foto.dk/images/thumb_Y1LBtIhwy81gjGYX1VZwIczZM1dp56A30cKWCQAU.jpg"></div>
            <div class="photo"><img alt="DJI_0477.jpg"
                    src="https://kirke-foto.dk/images/thumb_K8mqFvoNmOUgPtQQvrMT2Q75kii86r7Rw7T21g19.jpg"></div>
            <div class="photo"><img alt="DJI_0475.jpg"
                    src="https://kirke-foto.dk/images/thumb_lLc57vfmRlzgQhnvTdm2D8PpOoHrzumGZRPkvAdc.jpg"></div>
            <div class="photo"><img alt="DJI_0474.jpg"
                    src="https://kirke-foto.dk/images/thumb_ZgSUQq1hjj9vNxgdn13QbHFGv59aNBpcjfO0vYk6.jpg"></div>
            <div class="photo"><img alt="DJI_0478.jpg"
                    src="https://kirke-foto.dk/images/thumb_ec0VNtOZIRArL3cKD2onJJHofFkqCo65jdiMC3sn.jpg"></div>
            <div class="photo"><img alt="DJI_0481.jpg"
                    src="https://kirke-foto.dk/images/thumb_mk1vLFd37KMvLpcLnU7URUMQdohtYcX5K2LB5rqc.jpg"></div>
            <div class="photo"><img alt="DJI_0479.jpg"
                    src="https://kirke-foto.dk/images/thumb_fj5N9OoxICPfgyorcDAwEioxq498CZ8tQAom0EU8.jpg"></div>
        </div>
    </div>
</div>
@script
    <script>
        console.log('HEJ');
        document.addEventListener('livewire:load', function() {
            // Initialize Nice Select2
            console.log('HERE');
            niceSelect2.init();
        });

        Livewire.on('select2Updated', () => {
            niceSelect2.update();
        });
    </script>
@endscript
