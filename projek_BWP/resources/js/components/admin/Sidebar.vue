<template>
  <aside class="admin-sidebar">
    <div class="admin-brand">
      <div class="brand-icon">💎</div>
      <div>
        <p class="brand-label mb-1">Intelligence</p>
        <small>Admin Portal</small>
      </div>
    </div>

    <nav class="admin-menu">
      <button
        v-for="item in menuItems"
        :key="item.name"
        class="admin-menu-btn"
        :class="{ active: modelValue === item.name }"
        @click="$emit('select', item); $emit('update:modelValue', item.name)"
      >
        <span class="menu-icon">{{ item.icon }}</span>
        <span class="menu-label">{{ item.name }}</span>
      </button>
    </nav>

    <div class="admin-sidebar-footer">
      <p class="sidebar-tip">Need help? Our concierge is on standby.</p>
      <button class="admin-pill-btn danger w-100" @click="logout">Sign Out</button>
    </div>
  </aside>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const props = defineProps({
  modelValue: { type: String, default: 'Dashboard' },
  menuItems: {
    type: Array,
    default: () => (
      [
        { name: 'Dashboard', icon: '🏠' },
        { name: 'Movie Catalog', icon: '🎬' },
        { name: 'Cinema Catalog', icon: '🎟️' },
        { name: 'TixFun Catalog', icon: '🎉' },
        { name: 'Food Catalog', icon: '🍿' },
        { name: 'Blogs Lists', icon: '📝' },
        { name: 'Transaction Log', icon: '📈' }
      ]
    )
  }
})
const emit = defineEmits(['update:modelValue', 'select'])

const logout = async () => {
  try {
    await axios.post('/logout')
    // Router in admin app has no named "login" route; send user to public login page
    window.location.href = '/login'
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>

<style scoped>
.admin-sidebar {
    width: 290px;
    background: linear-gradient(180deg, #ffe086 0%, #f9c553 45%, #e8a735 100%);
    padding: 2.5rem 1.8rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    position: relative;
    z-index: 1;
    border-right: 1px solid rgba(255, 255, 255, 0.25);
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.brand-icon {
    width: 46px;
    height: 46px;
    border-radius: 16px;
    background: linear-gradient(135deg, #ffd86f, #f5b942);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 15px 30px rgba(245, 185, 66, 0.45);
}

.brand-label {
    margin: 0;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-weight: 700;
    color: rgba(54, 33, 7, 0.7);
}

.admin-brand small {
    font-weight: 600;
    color: #7a4d03;
}

.admin-menu {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.admin-menu-btn {
    border: none;
    border-radius: 0;
    background: transparent;
    padding: 0.95rem 0.75rem;
    border-radius: 5px;
    font-weight: 600;
    color: rgba(44, 28, 6, 0.7);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    width: 100%;
    border-left: 3px solid transparent;
    justify-content: flex-start;
}

.admin-menu-btn:hover {
    color: rgba(44, 28, 6, 0.95);
    background: rgba(255, 255, 255, 0.35);
}

.admin-menu-btn.active {
    color: #2d1b05;
    border-left-color: rgba(44, 28, 6, 0.9);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
}

.menu-icon {
    width: 32px;
    height: 32px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
}

.menu-label {
    flex: 1;
    text-align: left;
}

.admin-sidebar-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.sidebar-tip {
    font-size: 0.85rem;
    color: rgba(44, 28, 6, 0.65);
    margin: 0;
}

.admin-pill-btn {
    border: none;
    border-radius: 999px;
    background: linear-gradient(135deg, #ffd86f 0%, #f5b942 100%);
    color: #1f1f1f;
    font-weight: 700;
    padding: 0.65rem 1.5rem;
    letter-spacing: 0.01em;
    box-shadow: 0 15px 35px rgba(245, 185, 66, 0.45);
    transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
}

.admin-pill-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 20px 40px rgba(245, 185, 66, 0.5);
}

.admin-pill-btn.small {
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
}

.admin-pill-btn.ghost {
    background: rgba(13, 110, 253, 0.08);
    color: #0d47a1;
    box-shadow: none;
}

.admin-pill-btn.ghost:hover {
    box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
}

.admin-pill-btn.danger {
    background: linear-gradient(135deg, #ff7b7b 0%, #f54b64 100%);
    color: #ffffff;
    box-shadow: 0 18px 35px rgba(245, 75, 100, 0.35);
}

@media (max-width: 992px) {
    .admin-layout {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .admin-sidebar-footer {
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .admin-menu {
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .admin-main {
        padding: 2rem 1.25rem;
    }

    .admin-sidebar {
        padding: 1.5rem;
    }

    .admin-menu-btn {
        width: 100%;
    }
}
</style>