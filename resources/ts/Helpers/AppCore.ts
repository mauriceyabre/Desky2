export default function AppCore() {
    const COLORS: string[] = [
        'white',
        'primary',
        'secondary',
        'success',
        'info',
        'warning',
        'danger',
        'light',
        'dark'
    ];

    const init = () => {
        // @ts-ignore
        KTComponents.init();
    }

    return {
        COLORS,
        init
    }
}
