/* ========================================================
   js/script.js
   สคริปต์เสริมฝั่ง Client สำหรับ "ระบบแบ่งปันเสื้อผ้าและของใช้มือสอง"
   ======================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* ---------- แสดงตัวอย่างรูปภาพก่อน Upload ---------- */
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!file) {
                return;
            }

            // ตรวจสอบชนิดไฟล์ฝั่ง Client (ฝั่ง Server ยังตรวจซ้ำอีกครั้งเพื่���ความปลอดภัย)
            if (!allowedTypes.includes(file.type)) {
                alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น (JPG, PNG, GIF, WEBP)');
                this.value = '';
                imagePreview.src = '';
                imagePreview.classList.add('d-none');
                return;
            }

            // ตรวจสอบขนาดไฟล์ไม่เกิน 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('ขนาดไฟล์ต้องไม่เกิน 5MB');
                this.value = '';
                imagePreview.src = '';
                imagePreview.classList.add('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    /* ---------- กรองรายการอัตโนมัติเมื่อเปลี่ยน dropdown ---------- */
    document.querySelectorAll('.auto-submit-filter').forEach(function (el) {
        el.addEventListener('change', function () {
            el.closest('form').submit();
        });
    });

    /* ---------- ยืนยันก่อนลบข้อมูล ---------- */
    document.querySelectorAll('.confirm-delete').forEach(function (el) {
        el.addEventListener('click', function (e) {
            const msg = el.getAttribute('data-confirm') || 'ยืนยันการลบรายการนี้ใช่หรือไม่?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    /* ---------- ปิด Alert อัตโนมัติหลัง 4 วินาที ---------- */
    document.querySelectorAll('.alert-auto-dismiss').forEach(function (el) {
        setTimeout(function () {
            if (window.bootstrap && bootstrap.Alert) {
                const alertInstance = bootstrap.Alert.getOrCreateInstance(el);
                alertInstance.close();
            } else {
                el.style.display = 'none';
            }
        }, 4000);
    });

    /* ---------- Toggle แสดง/ซ่อนช่องรายละเอียดเพิ่มเติมของรายงาน ---------- */
    const reasonSelect = document.getElementById('reason');
    const detailWrap = document.getElementById('detailWrap');
    if (reasonSelect && detailWrap) {
        reasonSelect.addEventListener('change', function () {
            detailWrap.classList.remove('d-none');
        });
    }
});