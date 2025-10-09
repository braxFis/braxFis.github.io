function handleShare(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title || 'Check this out!');
    let shareUrl = '';

    switch (platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`;
            break;
        default:
            alert('Platform not supported');
            return;
    }

    window.open(shareUrl, '_blank', 'noopener,noreferrer');
}

document.querySelectorAll('.share-button').forEach(button => {
    button.addEventListener('click', () => {
        handleShare(button.getAttribute('data-platform'));
    });
});