var img  = document.querySelectorAll('.img_show')
var show = document.querySelector('.show')
var show_img = show.querySelector('img')
var show_box = show.querySelector('.show-box')
var show_introduce = show.querySelector('.img-introduce')
var body = document.body
// Description for the journals
const arr_introduce =[
  'Diary 1 ',
  'Diary 2',
  'Diary 3',
  'Diary 4',
  'Travel Journal: Germany',
  'Travel Journal',
  'The making of George Eliot',
  'Journy to Normandy',
]
img.forEach((item,index) => {
 item.addEventListener('click',function(){
  show_img.src= item.src
  show.style.display = 'block'
  show_introduce.innerHTML = arr_introduce[index]
 })
})
body.addEventListener('click',function(e){
if(e.target===show)show.style.display = 'none'
},true)
