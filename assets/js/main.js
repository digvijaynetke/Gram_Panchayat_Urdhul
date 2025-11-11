// Basic interactions and data fetching placeholders
(function(){
	// Hero slider
	const slides = document.querySelectorAll('.slide');
	let current = 0;
	setInterval(()=>{
		slides[current].classList.remove('active');
		current = (current + 1) % slides.length;
		slides[current].classList.add('active');
	}, 5000);

	// Video enlarge
	const aboutVideo = document.getElementById('aboutVideo');
	const mediaModal = document.getElementById('mediaModal');
	const mediaBody = document.getElementById('mediaModalBody');
	function openMediaModal(node){
		mediaBody.innerHTML = '';
		mediaBody.appendChild(node);
		mediaModal.classList.add('show');
		mediaModal.setAttribute('aria-hidden','false');
	}
	function closeModals(){
		document.querySelectorAll('.modal').forEach(m=>{
			m.classList.remove('show');
			m.setAttribute('aria-hidden','true');
		});
		// Stop any playing video inside media modal
		mediaBody.querySelectorAll('video').forEach(v=>{ try{ v.pause(); }catch(e){} });
	}
	if(aboutVideo){
		aboutVideo.addEventListener('click', ()=>{
			const big = document.createElement('video');
			big.src = aboutVideo.currentSrc || aboutVideo.src;
			big.controls = true;
			big.autoplay = true;
			big.style.width = '100%';
			openMediaModal(big);
		});
	}
	document.querySelectorAll('[data-close]').forEach(btn=>{
		btn.addEventListener('click', closeModals);
	});

	// Complaint modal openers
	function openComplaint(){
		const m = document.getElementById('complaintModal');
		m.classList.add('show');
		m.setAttribute('aria-hidden','false');
	}
	const open1 = document.getElementById('openComplaint');
	const open2 = document.getElementById('openComplaint2');
	if(open1) open1.addEventListener('click', openComplaint);
	if(open2) open2.addEventListener('click', openComplaint);

	// Complaint async submit (progressive enhancement)
	const complaintForm = document.getElementById('complaintForm');
	if(complaintForm){
		complaintForm.addEventListener('submit', async (e)=>{
			e.preventDefault();
			const success = document.getElementById('complaintSuccess');
			const error = document.getElementById('complaintError');
			success.style.display = 'none';
			error.style.display = 'none';
			const fd = new FormData(complaintForm);
			try{
				const res = await fetch(complaintForm.action, { method: 'POST', body: fd });
				const data = await res.json();
				if(data && data.ok){
					success.textContent = data.message || 'Complaint submitted successfully.';
					success.style.display = 'block';
					complaintForm.reset();
				}else{
					error.textContent = (data && data.message) || 'Failed to submit complaint.';
					error.style.display = 'block';
				}
			}catch(err){
				error.textContent = 'Network error. Please try again later.';
				error.style.display = 'block';
			}
		});
	}

	// Image modals (QRs and gallery)
	document.addEventListener('click', (e)=>{
		const t = e.target;
		if(t.classList && (t.classList.contains('qr-img') || t.closest('.gallery-grid img'))){
			const img = document.createElement('img');
			img.src = t.src;
			img.style.width = '100%';
			openMediaModal(img);
		}
	});

	// Language switcher (front-end only; backend can supply translations later)
	const langButtons = document.querySelectorAll('.lang-btn');
	let currentLang = localStorage.getItem('lang') || 'en';
	function applyTranslations(bundle){
		document.querySelectorAll('[data-i18n]').forEach(node=>{
			const key = node.getAttribute('data-i18n');
			if(bundle[key]) node.textContent = bundle[key];
		});
	}
	async function loadLang(lang){
		try{
			const res = await fetch(`assets/i18n/${lang}.json`);
			const data = await res.json();
			applyTranslations(data);
		}catch(e){
			// No-op on missing file
		}
	}
	if(langButtons.length){
		langButtons.forEach(btn=>{
			btn.addEventListener('click', ()=>{
				const lang = btn.dataset.lang;
				currentLang = lang;
				localStorage.setItem('lang', lang);
				loadLang(lang);
			});
		});
		loadLang(currentLang);
	}
})(); 


