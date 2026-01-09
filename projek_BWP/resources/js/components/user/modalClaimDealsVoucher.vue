<template>
  <div v-if="show" class="voucher-overlay" @click.self="$emit('close')">
    <div class="voucher-modal">
      <button class="voucher-close" @click="$emit('close')">×</button>

      <div class="voucher-icon">🎁</div>

      <h4 class="fw-bold mt-3">Voucher Claimed!</h4>

      <p class="text-muted small">
        You've successfully claimed the
        <strong>{{ voucher.title }}</strong> voucher.
      </p>

      <div class="voucher-code-wrapper" @click="copyCode">
        <div class="voucher-code-label">PROMO CODE</div>
        <div class="voucher-code">{{ voucher.code }}</div>
      </div>

      <p class="voucher-desc">
        Apply this code during checkout to receive your
        <strong>{{ voucher.discount }}</strong> discount.
        Valid for single use only.
      </p>

      <button class="voucher-ok-btn" @click="$emit('close')">
        GOT IT, THANKS!
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  show: Boolean,
  voucher: {
    type: Object,
    required: true
  }
})

const copyCode = async () => {
  if (!props.voucher?.code) return
  await navigator.clipboard.writeText(props.voucher.code)
}
</script>

<style scoped>
/* (CSS sama persis seperti sebelumnya) */

.voucher-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 3000;
}

.voucher-modal {
  background: #fff;
  width: 100%;
  max-width: 420px;
  border-radius: 22px;
  padding: 28px 24px 24px;
  text-align: center;
  position: relative;
  animation: popIn 0.25s ease;
}

@keyframes popIn {
  from { transform: scale(0.9); opacity: 0 }
  to { transform: scale(1); opacity: 1 }
}

.voucher-close {
  position: absolute;
  top: 14px;
  right: 16px;
  border: none;
  background: none;
  font-size: 22px;
  color: #999;
  cursor: pointer;
}

.voucher-icon {
  width: 64px;
  height: 64px;
  background: #e6f9fb;
  color: #0dcaf0;
  font-size: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.voucher-code-wrapper {
  border: 2px dashed #dbe5f1;
  border-radius: 14px;
  padding: 14px;
  margin: 20px 0;
  cursor: pointer;
}

.voucher-code-label {
  font-size: 11px;
  letter-spacing: 1px;
  color: #9aa4b2;
}

.voucher-code {
  font-size: 22px;
  font-weight: 800;
  letter-spacing: 2px;
  color: #1f3c88;
}

.voucher-desc {
  font-size: 13px;
  color: #6c757d;
}

.voucher-ok-btn {
  width: 100%;
  border: none;
  background: #0b1b33;
  color: #fff;
  padding: 14px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 14px;
}
</style>
