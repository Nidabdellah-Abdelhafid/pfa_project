var map = document.querySelector('#map')

var paths = map.querySelectorAll('.map__image a')

var links = map.querySelectorAll('.map__list a')


if (NodeList.prototype.forEach === undefined) {
    Nodelist.prototype.forEach = function(callback) {
        [].forEach.call(this, callback)
    }
}



paths.forEach(function(path) {
    path.addEventListener('mouseenter', function() {
        var id = this.id.replace('MA-', '')
        map.querySelectorAll('.is-active').forEach(function(item) {
            item.classList.remove('is-active')
        })
        document.querySelector('#ma-' + id).classList.add('is-active')
        document.querySelector('#MA-' + id).classList.add('is-active')
    })
})

links.forEach(function(link) {
    link.addEventListener('mouseenter', function() {
        var id = this.id.replace('ma-', '')
        map.querySelectorAll('.is-active').forEach(function(item) {
            item.classList.remove('is-active')
        })
        document.querySelector('#ma-' + id).classList.add('is-active')
        document.querySelector('#MA-' + id).classList.add('is-active')
    })
})


map.addEventListener('mouseover', function() {
    map.querySelectorAll('.is-active').forEach(function(item) {
        item.classList.remove('is-active')
    })
})







var ref1 = $('#bh1');
var popup1 = $('#popup1');
popup1.hide();
ref1.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup1.show();
    var popper1 = new Popper(ref1, popup1, {
        placement: 'top',
        modifiers: {
            flip: {
                behavior: ['right', 'right', 'left', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref2 = $('#bh2');
var popup2 = $('#popup2');
popup2.hide();
ref2.click(function() {
    popup1.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup2.show();
    var popper2 = new Popper(ref2, popup2, {
        placement: 'right',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref3 = $('#bh3');
var popup3 = $('#popup3');
popup3.hide();
ref3.click(function() {
    popup2.hide();
    popup1.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup3.show();
    var popper3 = new Popper(ref3, popup3, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref4 = $('#bh4');
var popup4 = $('#popup4');
popup4.hide();
ref4.click(function() {
    popup2.hide();
    popup3.hide();
    popup1.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup4.show();
    var popper4 = new Popper(ref4, popup4, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref5 = $('#bh5');
var popup5 = $('#popup5');
popup5.hide();
ref5.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup1.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup5.show();
    var popper5 = new Popper(ref5, popup5, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref6 = $('#bh6');
var popup6 = $('#popup6');
popup6.hide();
ref6.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup1.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup6.show();
    var popper6 = new Popper(ref6, popup6, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref7 = $('#bh7');
var popup7 = $('#popup7');
popup7.hide();
ref7.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup1.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup7.show();
    var popper7 = new Popper(ref7, popup7, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref8 = $('#bh8');
var popup8 = $('#popup8');
popup8.hide();
ref8.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup1.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup8.show();
    var popper8 = new Popper(ref8, popup8, {
        placement: 'right',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref9 = $('#bh9');
var popup9 = $('#popup9');
popup9.hide();
ref9.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup1.hide();
    popup10.hide();
    popup11.hide();
    popup12.hide();

    popup9.show();
    var popper9 = new Popper(ref9, popup9, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref10 = $('#bh10');
var popup10 = $('#popup10');
popup10.hide();
ref10.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup1.hide();
    popup11.hide();
    popup12.hide();

    popup10.show();
    var popper10 = new Popper(ref10, popup10, {
        placement: 'left',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref11 = $('#bh11');
var popup11 = $('#popup11');
popup11.hide();
ref11.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup1.hide();
    popup12.hide();

    popup11.show();
    var popper11 = new Popper(ref11, popup11, {
        placement: 'right',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});



var ref12 = $('#bh12');
var popup12 = $('#popup12');
popup12.hide();
ref12.click(function() {
    popup2.hide();
    popup3.hide();
    popup4.hide();
    popup5.hide();
    popup6.hide();
    popup7.hide();
    popup8.hide();
    popup9.hide();
    popup10.hide();
    popup11.hide();
    popup1.hide();

    popup12.show();
    var popper12 = new Popper(ref12, popup12, {
        placement: 'right',
        modifiers: {
            flip: {
                behavior: ['left', 'right', 'top', 'bottom']
            },
            offset: {
                enabled: true,
                offset: '0,10'
            }
        }

    });
});