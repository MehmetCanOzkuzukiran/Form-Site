$(document).ready(function () {
    function highlightText(text, term) {
        return text.replace(new RegExp('(' + term + ')', 'ig'), function (_, match) {
            return '<span class="highlight">' + match + '</span>';
        });
    }

    function performSearch(term) {
        $('.post-content .post-title a').each(function () {
            var linkText = $(this).text();
            var highlightedText = highlightText(linkText, term);
            $(this).html(highlightedText);
        });

        // Scroll to the first match in the entire document
        var firstMatch = $('.highlight:first');
        if (firstMatch.length > 0) {
            $('html, body').animate({
                scrollTop: firstMatch.offset().top
            }, 500);
        }
    }

    function clearHighlight() {
        $('.post-content .post-title a').each(function () {
            var originalText = $(this).data('original-text') || $(this).text();
            $(this).text(originalText);
        });
    }

    $('#searchInput').on('input', function () {
        var searchTerm = $(this).val().trim();
        $('.highlight').removeClass('highlight');

        if (searchTerm !== '') {
            performSearch(searchTerm);
        } else {
            clearHighlight();
        }
    });
});
