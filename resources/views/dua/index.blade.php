@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dua-styles.css') }}">
@endpush

@section('content')
<div class="dua-container">
    <h1 class="dua-title">Duas</h1>

    <div class="dua-controls">
        <div class="tabs">
            <button class="tab" data-category="Ramadan">Ramadan Duas</button>
            <button class="tab active" data-category="General">General Duas</button>
            <button class="tab" data-category="Occasion">Occasion Duas</button>
        </div>

        <div class="inline-actions">
            <input id="duaSearch" placeholder="Search duas (Arabic, transliteration, translation)" />
            <select id="subsectionFilter">
                <option value="">All subsections</option>
                <option value="after_meal">After finishing meal</option>
                <option value="arafat">Arafat</option>
            </select>
            <button id="addDuaBtn">+ Add Dua</button>
        </div>
    </div>

    <div id="duasList" class="duas-list"></div>

    <!-- minimal modal for add/edit -->
    <div id="duaModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3 id="modalTitle">Add Dua</h3>
            <input id="duaCategory" placeholder="Category" />
            <input id="duaSubsection" placeholder="Subsection (optional)" />
            <input id="duaTitle" placeholder="Title (optional)" />
            <textarea id="duaArabic" placeholder="Arabic text"></textarea>
            <textarea id="duaTranslit" placeholder="Transliteration"></textarea>
            <textarea id="duaTrans" placeholder="Translation"></textarea>
            <div class="modal-actions">
                <button id="saveDuaBtn">Save</button>
                <button id="cancelDuaBtn">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
const api = {
    list: (params) => fetch(`/duas?${new URLSearchParams(params)}`).then(r => r.json()),
    store: (data) => fetch('/duas', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify(data)}).then(r=>{
        if (r.status===401) throw {unauth:true}; return r.json();
    }),
    update: (id, data) => fetch(`/duas/${id}`, {method:'PUT', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify(data)}).then(r=>{ if (r.status===401) throw {unauth:true}; return r.json(); }),
    destroy: (id) => fetch(`/duas/${id}`, {method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(r=>{ if (r.status===401) throw {unauth:true}; return r.json(); })
}

let state = {category:'General', subsection:'', q:''};

async function loadDuas(){
    const res = await api.list({category: state.category, subsection: state.subsection, q: state.q});
    const list = document.getElementById('duasList');
    list.innerHTML = '';
    if (!res.duas || res.duas.data.length===0){ list.innerHTML = '<p class="muted">No duas found.</p>'; return; }
    res.duas.data.forEach(d=>{
        const el = document.createElement('div'); el.className='dua-item';
        el.innerHTML = `
            <h4 class="dua-title-small">${escapeHtml(d.title||'')}</h4>
            <p class="dua-arabic">${escapeHtml(d.arabic_text||'')}</p>
            <p class="dua-translit">${escapeHtml(d.transliteration||'')}</p>
            <p class="dua-translation">${escapeHtml(d.translation||'')}</p>
            <div class="dua-meta">${d.subsection?'<span class="badge">'+escapeHtml(d.subsection)+'</span>':''} ${d.user_id?'<small class="muted">(user)</small>':''}</div>
            <div class="dua-actions">
                <button class="edit" data-id="${d.id}">Edit</button>
                <button class="delete" data-id="${d.id}">Delete</button>
            </div>
        `;
        list.appendChild(el);
    });
    // attach events
    document.querySelectorAll('.dua-actions .edit').forEach(btn=>btn.addEventListener('click', onEdit));
    document.querySelectorAll('.dua-actions .delete').forEach(btn=>btn.addEventListener('click', onDelete));
}

function onEdit(e){
    const id = e.target.dataset.id;
    // find dua data in DOM or refetch
    // for simplicity open modal and store id
    openModal('Edit Dua', id);
}

function onDelete(e){
    const id = e.target.dataset.id;
    if (!confirm('Delete this dua?')) return;
    api.destroy(id).then(()=> loadDuas()).catch(err=>{ if (err.unauth) alert('Please login to delete'); else alert('Error deleting'); });
}

function openModal(title, id=null){
    document.getElementById('modalTitle').textContent = title;
    window._editingDuaId = id;
    document.getElementById('duaModal').style.display='block';
}
function closeModal(){ document.getElementById('duaModal').style.display='none'; }

document.getElementById('addDuaBtn').addEventListener('click', ()=> openModal('Add Dua'));
document.getElementById('cancelDuaBtn').addEventListener('click', ()=> closeModal());

document.getElementById('saveDuaBtn').addEventListener('click', ()=>{
    const payload = {
        category: document.getElementById('duaCategory').value || state.category,
        subsection: document.getElementById('duaSubsection').value,
        title: document.getElementById('duaTitle').value,
        arabic_text: document.getElementById('duaArabic').value,
        transliteration: document.getElementById('duaTranslit').value,
        translation: document.getElementById('duaTrans').value,
    };
    if (window._editingDuaId){
        api.update(window._editingDuaId, payload).then(()=>{ closeModal(); loadDuas(); }).catch(err=>{ if (err.unauth) alert('Please login to edit'); else alert('Error'); });
    } else {
        api.store(payload).then(()=>{ closeModal(); loadDuas(); }).catch(err=>{ if (err.unauth) alert('Please login to add'); else alert('Error'); });
    }
});

// search & tabs
document.querySelectorAll('.tabs .tab').forEach(t=> t.addEventListener('click', (e)=>{ document.querySelectorAll('.tabs .tab').forEach(x=>x.classList.remove('active')); e.target.classList.add('active'); state.category = e.target.dataset.category; loadDuas(); }));
document.getElementById('duaSearch').addEventListener('input', (e)=>{ state.q = e.target.value; setTimeout(()=> loadDuas(), 300); });
document.getElementById('subsectionFilter').addEventListener('change', (e)=>{ state.subsection = e.target.value; loadDuas(); });

function escapeHtml(text){ const d=document.createElement('div'); d.textContent = text; return d.innerHTML; }

// initial load
loadDuas();
</script>
@endsection
