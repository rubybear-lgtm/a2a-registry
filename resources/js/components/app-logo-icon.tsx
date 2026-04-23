type Props = {
    className?: string;
};

export default function AppLogoIcon({ className }: Props) {
    return (
        <img
            src="/a2a-icon.png"
            alt=""
            aria-hidden="true"
            className={['dark:invert', className].filter(Boolean).join(' ')}
        />
    );
}
