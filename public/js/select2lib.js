function sel2_datalokal(vselector, vdata={}, vModal=null, vAllow=false, vTags=false){
  $(vselector).select2({
    data: vdata,
    placeholder: '- pilih -',
    multiple: vTags,
    dropdownParent: $(vselector).parent(),
    allowClear: vAllow,
  });
}

function sel2_jeniskelamin(vselector, vModal=null,vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"L",text:"Laki-Laki"}, 
          {id:"P",text:'Perempuan'}];
  sel2_datalokal(vselector, dataparent, vModal, vallow, vtags);
}
      
function sel2_aktif1(vselector, vModal=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"1",text:"Ya"}, 
          {id:"0",text:'Tidak'}];
  sel2_datalokal(vselector, dataparent, vModal, vallow, vtags);
}
              
function sel2_aktif2(vselector, vModal=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"1",text:"Aktif"}, 
          {id:"0",text:'Tidak Aktif'}];
  sel2_datalokal(vselector, dataparent, vModal, vallow, vtags);
}

function sel2_tahun(vselector, vModal=null, vallow=false, vtags=false){
  let thn=new Date().getFullYear();
  thn=thn+1;
  let list_thn=[];
  let x=1;
  list_thn[0]={id:"",text:""};
  for(i=(thn);i>=(thn-4);i--){
    list_thn[x]={id:i,text:i};
    x++;
  }
  sel2_datalokal(vselector, list_thn, vModal, vallow, vtags);
}