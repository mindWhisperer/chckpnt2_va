export const getCookies = () => Object.fromEntries(
    document.cookie
        .split('; ')
        .map(v => v
            .split(/=(.*)/s)
            .map(decodeURIComponent),
        ),
);

export const isValidImageUrl = (url) => {
    try {
        new URL(url);
        return (url.match(/\.(jpeg|jpg|gif|png|webp)$/) != null);
    } catch {
        return false;
    }
}
