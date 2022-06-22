let observers = [];

document.querySelectorAll('.movie.modal').forEach(
    (node) => {
        let observer = new MutationObserver(function(){
            if(node.style.display != 'none'){
                fetch('/api/video/' + node.dataset.movieId)
                    .then((response) => {
                        response
                            .json()
                            .then((content) => {
                                node
                                    .querySelector('object')
                                    .setAttribute('data', content.link)
                                ;
                            })
                        ;
                    })
            }
        });
        observer.observe(node, { attributes: true, childList: true });

        observers.push(observer);
    }
);