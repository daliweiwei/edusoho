import Postmate from 'postmate'

let search = window.location.search.replace('?path=', '')
search = search ? decodeURIComponent(search) : '/'

let baseUrl = ''
if (process.env.NODE_ENV === 'development') {
  baseUrl = 'http://localhost:8080/console-pc' + search
} else if (process.env.NODE_ENV === 'production') {
  const iframeUrl = $('#iframe-url').val()

  baseUrl += iframeUrl.split('/?')[0] + search
  baseUrl += search.indexOf('?') > -1 ? '&' : '?'
  baseUrl += baseUrl.split('/?')[1]
}

const handshake = new Postmate({
  container: document.getElementById('some-div'),
  url: baseUrl,
  name: 'my-iframe-name',
  classListArray: ['iframe-full']
});

handshake.then(child => {
  child.on('route:update', data => {
    const { origin, pathname } = window.location
    const path = encodeURIComponent(data.path)

    window.history.pushState('', '', `${origin}${pathname}?path=${path}`)
  });

  child.on('route:jump', data => {
    if (data.target === '_blank') {
      window.open(data.newUrl)

      return
    }

    window.location.href = data.newUrl
  })

  child.call('setOptions', JSON.parse($('#options').val()))

  window.addEventListener('popstate', function(event) {
    const search = event.target.location.search.replace('?path=', '')

    if (!search) return

    child.call('routerBack')
  })
}); 


