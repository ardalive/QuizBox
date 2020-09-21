let tdDescr  = document.getElementsByClassName('ceilForDescription');
for (let i = 0 ; i < tdDescr.length; i++){
    if (tdDescr[i].innerText.length >=200){
        tdDescr[i].innerText = tdDescr[i].innerText.substr(0, 200) + '...'
    }
}

let tdName = document.getElementsByClassName('ceilForName');
for (let i = 0; i < tdName.length; i++){
    if (tdName[i].innerText.length >= 15){
        tdName[i].innerText = tdName[i].innerText.substr(0, 15) + '...'
    }
}