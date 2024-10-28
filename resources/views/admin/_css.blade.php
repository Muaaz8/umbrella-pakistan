
<style type="text/css">


#href_link{
  text-decoration: none;
  color: black;
}
#href_link:visited{
  color: black;
}
.icons_{
  display: inline;
  float: right;
}
.notification_{

  position: relative;
  display: inline-block;
}
.number_{
    line-height: 15px;
    height: 18px;
    width: 20px;
    background-color: black;
    color: white;
    text-align: center;
    position: absolute;
    left: 25px;
    font-size: 10px;
    bottom: 23px;
}
.number_:empty {
   display: none;
}
.notBtn_{
  transition: 0.5s;
  cursor: pointer
}
.bell_note{
  font-size: 30px;
  padding-bottom: 5px;
  color: white;
  margin-right: 20px;
  margin-left: 5px;
}
.box_{
  width: 350px;
  height: 0px;
  /* border-radius: 10px; */
  transition: 0.5s;
  position: absolute;
  overflow-y: scroll;
  left: -300px;
  background-color: #F4F4F4;
  -webkit-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.2);
  -moz-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  cursor: context-menu;
}

.notBtn_:hover > .box_{
  height: 60vh;
}
.content_{
  padding: 20px;
  color: black;
  vertical-align: middle;
  text-align: left;
}
.gry_{
  background-color: #F4F4F4;
}
.top_{
  color: black;
  padding: 10px
}
.display_{
    height: 90%;
  position: relative;
}
.display1_{
    background-color: rgb(44 96 177);
    text-align: center;
    color: white;
    top: 0px;
    bottom: 0px;
    height: 10%;
  position: relative;
}
.cont_{
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: #F4F4F4;
}
.cont_:empty{
  display: none;
}
.stick_{
  text-align: center;
  display: block;
  font-size: 50pt;
  padding-top: 70px;
  padding-left: 80px
}
.stick_:hover{
  color: black;
}
.cent_{
  text-align: center;
  display: block;
}

.sec_{
  padding: 25px 10px;
  transition: 0.5s;
}
.profCont_{
  padding-left: 50px;
}
.profile_{
  -webkit-clip-path: circle(50% at 50% 50%);
  clip-path: circle(50% at 50% 50%);
  width: 75px;
  float: left;
}
.txt_{
    vertical-align: top;
    font-size: 1.25rem;
    padding: 0px 10px 0px 10px;
}
.sub_{
  font-size: 1rem;
  color: grey;
}
.new_{
  border-style: none none solid none;
  border-color: rgb(39, 39, 141);
}
.sec_:hover{
  background-color: #BFBFBF;
}
































#noteLink{
color: black;
}
.readColor{
  background-color: white !important;
}
.readColor :hover{
  cursor: pointer;
  color: white;
  background: linear-gradient(45deg, #5e94e4 , #08295a);
}
.unreadColor :hover{
  cursor: pointer;
  color: white;
  background: linear-gradient(45deg, #5e94e4 , #08295a);
}

#time-range p {
    font-family:"Arial", sans-serif;
    font-size:14px;
    color:#333;
}
.ui-slider-horizontal {
    height: 8px;
    background: #D7D7D7;
    border: 1px solid #BABABA;
    box-shadow: 0 1px 0 #FFF, 0 1px 0 #CFCFCF inset;
    clear: both;
    margin: 8px 0;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    border-radius: 6px;
}
.ui-slider {
    position: relative;
    text-align: left;
}
.ui-slider-horizontal .ui-slider-range {
    top: -1px;
    height: 100%;
}
.ui-slider .ui-slider-range {
    position: absolute;
    z-index: 1;
    height: 8px;
    font-size: .7em;
    display: block;
    border: 1px solid #5BA8E1;
    box-shadow: 0 1px 0 #AAD6F6 inset;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    -khtml-border-radius: 6px;
    border-radius: 6px;
    background: #81B8F3;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #A0D4F5), color-stop(100%, #81B8F3));
    background-image: -webkit-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: -moz-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: -o-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: linear-gradient(top, #A0D4F5, #81B8F3);
}
.ui-slider .ui-slider-handle {
    border-radius: 50%;
    background: #F9FBFA;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #C7CED6), color-stop(100%, #F9FBFA));
    background-image: -webkit-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: -moz-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: -o-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: linear-gradient(top, #C7CED6, #F9FBFA);
    width: 22px;
    height: 22px;
    -webkit-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    -moz-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    -webkit-transition: box-shadow .3s;
    -moz-transition: box-shadow .3s;
    -o-transition: box-shadow .3s;
    transition: box-shadow .3s;
}
.ui-slider .ui-slider-handle {
    position: absolute;
    z-index: 2;
    width: 22px;
    height: 22px;
    cursor: default;
    border: none;
    cursor: pointer;
}
.ui-slider .ui-slider-handle:after {
    content:"";
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    top: 50%;
    margin-top: -4px;
    left: 50%;
    margin-left: -4px;
    background: #30A2D2;
    -webkit-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
    -moz-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 white;
    box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
}
.ui-slider-horizontal .ui-slider-handle {
    top: -.5em;
    margin-left: -.6em;
}
.ui-slider a:focus {
    outline:none;
}

#slider-range {
  width: 90%;
  margin: 0 auto;
}
/*#time-range {
  width: 400px;
}*/
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<style>
.medicine-bg{
    color:white  !important;
    background-color:#457FCA !important;
}
.lab-bg{
    color:white  !important;
    background-color:#457FCA !important;
}

.imaging-bg{
    color:white  !important;
    background-color:#457FCA !important;
}
.content{
  min-height:600px;
}
.n-top {
    z-index: 11 !important;
}
.m-top {
    z-index: 12 !important;
}
.dropdown-menu {
    position: absolute;
    top: 25%;
    right: 40%;
}
</style>
