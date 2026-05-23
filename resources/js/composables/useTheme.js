import { reactive } from 'vue'

const state = reactive({
    isDark: false,
})

export function useTheme() {
    function init() {
        const stored = localStorage.getItem('theme')
        const prefersDark = window.matchMedia('(prefers-color-scheme:dark)').matches

        state.isDark = stored ? stored === 'dark':prefersDark
        applyTheme()
    }

    function toggle() {
        state.isDark = !state.isDark
        applyTheme()
        localStorage.setItem('theme', state.isDark ? 'dark' : 'light')
    }

    function applyTheme() {
        document.documentElement.classList.toggle('dark', state.isDark)
    }

    return { state, init, toggle }
}
