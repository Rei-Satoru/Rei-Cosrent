

<?php $__env->startSection('title', 'Ulasan - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Ulasan untuk Pesanan #<?php echo e($order->id); ?></h2>
            <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-outline-primary">&larr; Kembali</a>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form id="reviewPageForm">
                    <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                    <input type="hidden" name="ulasan_id" value="<?php echo e($existingReview->id ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-stars" data-order-id="<?php echo e($order->id); ?>">
                            <?php for($i=1;$i<=5;$i++): ?>
                                <label class="me-1" style="cursor:pointer; font-size:1.6rem; color:#ddd;" data-value="<?php echo e($i); ?>">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" value="<?php echo e($existingReview->rating ?? 0); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ulasan</label>
                        <textarea name="review" class="form-control" rows="6" required><?php echo e($existingReview->review ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar (opsional)</label>
                        <?php for($j=1;$j<=5;$j++): ?>
                        <div class="mb-2">
                            <input type="file" name="gambar[]" accept="image/*" class="form-control gambar-input" data-order-id="<?php echo e($order->id); ?>" data-index="<?php echo e($j); ?>">
                            <div class="mt-1 preview-single" id="preview-<?php echo e($order->id); ?>-<?php echo e($j); ?>">
                                <?php $col = 'gambar_' . $j; ?>
                                <?php if(!empty($existingReview) && !empty($existingReview->$col)): ?>
                                    <img src="<?php echo e(asset('storage/' . $existingReview->$col)); ?>" alt="img" style="max-height:90px; display:block; margin-top:6px;"/>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <div class="d-flex">
                        <?php if(!empty($existingReview)): ?>
                        <button type="button" class="btn btn-danger me-auto" id="deleteReviewBtn" data-ulasan-id="<?php echo e($existingReview->id); ?>">Hapus Ulasan</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary me-2" id="cancelBtn">Batal</button>
                        <button type="button" class="btn btn-primary" id="saveReviewBtn">Simpan Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const orderId = '<?php echo e($order->id); ?>';
    const form = document.getElementById('reviewPageForm');

    // set initial star highlight
    document.querySelectorAll('.rating-stars').forEach(function(starWrap){
        const hiddenInput = form.querySelector('input[name="rating"]');
        const initial = parseInt(hiddenInput.value) || 0;
        Array.from(starWrap.children).forEach(function(lbl){
            const val = parseInt(lbl.getAttribute('data-value')) || 0;
            lbl.style.color = (val <= initial) ? '#ffc107' : '#ddd';
            lbl.setAttribute('tabindex', '0');

            lbl.addEventListener('click', function(e){
                const v = lbl.getAttribute('data-value');
                hiddenInput.value = v; 
                Array.from(starWrap.children).forEach(function(ch){
                    const chVal = parseInt(ch.getAttribute('data-value')) || 0;
                    ch.style.color = (chVal <= parseInt(v)) ? '#ffc107' : '#ddd';
                });
            });

            lbl.addEventListener('keydown', function(ev){
                if (ev.key === 'Enter' || ev.key === ' ') {
                    ev.preventDefault(); lbl.click();
                }
            });
        });
    });

    // per-input preview
    document.querySelectorAll('.gambar-input').forEach(function(inp){
        inp.addEventListener('change', function(){
            const idx = inp.getAttribute('data-index');
            const preview = document.getElementById('preview-' + orderId + '-' + idx);
            preview.innerHTML = '';
            if (inp.files && inp.files.length) {
                const f = inp.files[0];
                const url = URL.createObjectURL(f);
                const img = document.createElement('img');
                img.src = url; img.style.maxHeight = '90px'; img.style.display = 'block'; img.style.marginTop = '6px';
                preview.appendChild(img);
            }
        });
    });

    // save
    document.getElementById('saveReviewBtn').addEventListener('click', function(){
        const fd = new FormData(form);
        // collect files from inputs
        const fileInputs = form.querySelectorAll('input[name="gambar[]"]');
        const appended = [];
        fileInputs.forEach(function(fi){ if (fi.files && fi.files.length) {
            Array.from(fi.files).forEach(function(f){ if (appended.length < 5) { fd.append('gambar[]', f); appended.push(f); } });
        }});

        const rating = fd.get('rating');
        if (!rating || rating === '0') { alert('Pilih rating (bintang) terlebih dahulu'); return; }

        const ulasanId = fd.get('ulasan_id');
        let url = '/user/ulasan';
        if (ulasanId && ulasanId.length) { fd.append('_method', 'PUT'); url = '/user/ulasan/' + ulasanId; }

        const btn = this; btn.disabled = true; const original = btn.innerHTML; btn.innerHTML = 'Menyimpan...';
        fetch(url, { method: 'POST', headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json'}, body: fd })
            .then(r => r.json()).then(data => {
                if (data && data.success) {
                    // langsung kembali ke halaman Pesanan Saya
                    window.location.href = '<?php echo e(route('user.pesanan')); ?>';
                } else if (data && data.message) {
                    alert(data.message);
                } else {
                    alert('Gagal menyimpan ulasan');
                }
            }).catch(err => { console.error(err); alert('Terjadi kesalahan saat menyimpan ulasan'); })
            .finally(()=>{ btn.disabled=false; btn.innerHTML = original; });
    });

    // delete
    const delBtn = document.getElementById('deleteReviewBtn');
        if (delBtn) {
            delBtn.addEventListener('click', function(){
                const id = delBtn.getAttribute('data-ulasan-id');
                if (!confirm('Hapus ulasan?')) return;
                fetch('/user/ulasan/' + id, { method: 'DELETE', headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json'} })
                    .then(r => r.json()).then(data => { if (data && data.success) { window.location.href = '<?php echo e(route('user.pesanan')); ?>'; } else alert('Gagal menghapus ulasan'); })
                    .catch(e => { console.error(e); alert('Error'); });
            });
        }

    document.getElementById('cancelBtn').addEventListener('click', function(){ window.location.href = '<?php echo e(route('user.pesanan')); ?>'; });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/ulasan.blade.php ENDPATH**/ ?>