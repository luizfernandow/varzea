const toHHMMSS = function (string) {
    const secNum = parseInt(string, 10) // don't forget the second param
    let hours = Math.floor(secNum / 3600)
    let minutes = Math.floor((secNum - hours * 3600) / 60)
    let seconds = secNum - hours * 3600 - minutes * 60

    if (hours < 10) {
        hours = '0' + hours
    }
    if (minutes < 10) {
        minutes = '0' + minutes
    }
    if (seconds < 10) {
        seconds = '0' + seconds
    }
    return hours + ':' + minutes + ':' + seconds
}

export default ({ app }, inject) => {
    inject('toHHMMSS', (string) => toHHMMSS(string))
}
