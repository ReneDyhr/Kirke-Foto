<div class="content">
    <div>
        <h1 class="church-header">{{ $churchModel->name }}</h1><span
            class="church-breadcrumb">{{ $churchModel->parish->name }} - {{ $churchModel->parish->deanery->name }} -
            {{ $churchModel->parish->deanery->diocese->name }}</span>
        <p><a href="https://sogn.dk/{{ $churchModel->parish->url }}" target="_blank" rel="noreferrer">Gå til
                {{ $churchModel->parish->name }}
                via
                sogn.dk</a></p>
        <div class="church-text">Det er muligt at få billederne i fuld opløsning, hvis man er medlem af kirkens personale
            eller menighedsråd.</div><br>
        <div id="gallery" class="photos">
        </div>
        <div class="image-wrapper" id="viewer" style="display: none;">
            <div class="top" id="closeBtn"><i class="fa fa-times fa-2x"></i></div>
            <div class="top panorama-icon" id="panoramaIcon" style="display: none;">
                <img src="/images/360.png" alt="Panorama" />
            </div>
            <div class="previous" id="prevBtn"><i class="fa fa-chevron-left"></i></div>
            <div class="next" id="nextBtn"><i class="fa fa-chevron-right"></i></div>
            <div class="shadow"></div>
            <div class="image" id="imageContainer"></div>
            <div class="comment" id="comment"></div>
        </div>
    </div>
</div>
@script
    <script>
        console.log('HEJ');
        const images = JSON.parse('{!! json_encode($images) !!}');

        let currentIndex = 0;
        const body = document.querySelector('body');
        const gallery = document.getElementById('gallery');
        const viewer = document.getElementById('viewer');
        const imageContainer = document.getElementById('imageContainer');
        const comment = document.getElementById('comment');
        const panoramaIcon = document.getElementById('panoramaIcon');

        images.forEach((img, i) => {
            const div = document.createElement('div');
            div.className = 'photo';
            const thumb = document.createElement('img');
            thumb.src = '/images/church/thumb_' + img.path;
            thumb.alt = img.description;
            thumb.style.cursor = "pointer";
            thumb.onclick = () => openViewer(i);

            div.appendChild(thumb);
            if (img.panorama) {
                const panoramaThumb = document.createElement('img');
                panoramaThumb.className = 'panorama-image';
                panoramaThumb.src = '/images/360.png';
                div.appendChild(panoramaThumb);
            }
            gallery.appendChild(div);
        });

        function openViewer(index) {
            currentIndex = index;
            viewer.style.display = 'block';
            viewer.classList.remove('scale-in');
            void viewer.offsetWidth; // trigger reflow
            viewer.classList.add('scale-in');
            viewer.style.top = `${window.scrollY}px`;
            body.style.overflow = 'hidden';
            loadImage();
        }

        function closeViewer() {
            viewer.style.display = 'none';
            body.style.overflow = '';

            imageContainer.innerHTML = '';
        }

        function loadImage() {
            const img = images[currentIndex];
            imageContainer.innerHTML = '';
            panoramaIcon.style.display = img.panorama ? 'block' : 'none';

            if (img.panorama) {
                const pano = document.createElement('div');
                pano.id = "panorama";
                pano.style.width = '100%';
                pano.style.height = '100%';
                imageContainer.appendChild(pano);

                const viewerInstance = pannellum.viewer("panorama", {
                    type: "equirectangular",
                    autoLoad: true,
                    showControls: false,
                    disableKeyboardCtrl: true,
                    panorama: "/images/church/high_" + img.path
                });

                const controls = document.createElement('div');
                controls.id = "controls";
                controls.innerHTML = `
            <div class="ctrl" id="pan-up"><i class="fa fa-caret-up"></i></div>
            <div class="ctrl" id="pan-down"><i class="fa fa-caret-down"></i></div>
            <div class="ctrl" id="pan-left"><i class="fa fa-caret-left"></i></div>
            <div class="ctrl" id="pan-right"><i class="fa fa-caret-right"></i></div>
            <div class="ctrl" id="zoom-in"><i class="fa fa-plus"></i></div>
            <div class="ctrl" id="zoom-out"><i class="fa fa-minus"></i></div>
            <div class="ctrl" id="fullscreen"><i class="fa fa-expand"></i></div>
        `;
                pano.appendChild(controls);

                controls.querySelector('#pan-up').onclick = () => viewerInstance.setPitch(viewerInstance.getPitch() + 10);
                controls.querySelector('#pan-down').onclick = () => viewerInstance.setPitch(viewerInstance.getPitch() - 10);
                controls.querySelector('#pan-left').onclick = () => viewerInstance.setYaw(viewerInstance.getYaw() - 10);
                controls.querySelector('#pan-right').onclick = () => viewerInstance.setYaw(viewerInstance.getYaw() + 10);
                controls.querySelector('#zoom-in').onclick = () => viewerInstance.setHfov(viewerInstance.getHfov() - 10);
                controls.querySelector('#zoom-out').onclick = () => viewerInstance.setHfov(viewerInstance.getHfov() + 10);
                controls.querySelector('#fullscreen').onclick = () => viewerInstance.toggleFullscreen();
            } else {
                const imgEl = document.createElement('img');
                imgEl.src = "/images/church/high_" + img.path;
                imgEl.onload = () => {};
                imageContainer.appendChild(imgEl);
            }

            const date = new Date(img.date_taken);
            comment.innerHTML = `Taget: ${date.toLocaleDateString('da')}<br>${img.description}`;
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            loadImage();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            loadImage();
        }

        document.getElementById('closeBtn').onclick = closeViewer;
        document.getElementById('nextBtn').onclick = nextImage;
        document.getElementById('prevBtn').onclick = prevImage;

        document.addEventListener('keydown', e => {
            if (viewer.style.display === 'block') {
                if (e.key === 'Escape') closeViewer();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'ArrowLeft') prevImage();
            }
        });

        let touchStartX = 0;
        imageContainer.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].clientX);
        imageContainer.addEventListener('touchend', e => {
            const delta = e.changedTouches[0].clientX - touchStartX;
            if (delta > 50) prevImage();
            else if (delta < -50) nextImage();
        });
    </script>
@endscript
