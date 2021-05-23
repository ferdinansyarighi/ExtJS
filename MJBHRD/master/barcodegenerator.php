<?php 
include '../main/header.php'; 

session_start();
$username = "";
if(isset($_SESSION['usernamegps']))
{
    $username=$_SESSION['usernamegps'];
}
else
{
    $username="";
}
if($username!=""){
?>

<script type="text/javascript">
    function setupApp() {
        Ext.QuickTips.init();
        var store_asset = new Ext.data.JsonStore({
            id: 'store_asset_id',
            pageSize: 100,
            fields: [{
                name: 'DATA_ASSET'
            }, {
                name: 'DATA_GROUP'
            }, {
                name: 'DATA_AREA'
            }, {
                name: 'DATA_PLAT'
            }, {
                name: 'DATA_LAMBUNG'
            }, {
                name: 'DATA_MODEL'
            }, {
                name: 'DATA_TYPE'
            }, {
                name: 'DATA_BULAN'
            }, {
                name: 'DATA_TAHUN'
            }, {
                name: 'DATA_COMPANY'
            },{
                name: 'DATA_BARCODE'
            },{
                name: 'DATA_ORID'
            }],
            proxy: {
                type: 'ajax',
                url: 'barcode/isi_asset.php',
                reader: {
                    root: 'rows',
                }
            },
            listeners: {
                load: {
                    fn: function() {
                        maskgrid.hide();
                    }
                },
                scope: this
            }
        });

        var grid_asset = Ext.create('Ext.grid.Panel', {
            id: 'grid_asset_id',
            region: 'center',
            store: store_asset,
            loadMask: true,
            columns: [{
                xtype: 'rownumberer',
                id: 'row_id',
                header: 'No',
                width: 25
            }, {
                dataIndex: 'DATA_ASSET',
                header: 'No. Asset',
                width: 110
            }, {
                dataIndex: 'DATA_GROUP',
                header: 'Group',
                width: 90,
                hidden:true
            }, {
                dataIndex: 'DATA_AREA',
                header: 'Area',
                width: 100
            }, {
                dataIndex: 'DATA_PLAT',
                header: 'No. Plat',
                width: 90
            }, {
                dataIndex: 'DATA_LAMBUNG',
                header: 'No. Lambung',
                width: 80
            }, {
                dataIndex: 'DATA_TYPE',
                header: 'Merk',
                width: 70
            }, {
                dataIndex: 'DATA_MODEL',
                header: 'Model',
                width: 155
            }, {
                dataIndex: 'DATA_BULAN',
                header: 'Bulan',
                width: 90,
                hidden:true
            }, {
                dataIndex: 'DATA_TAHUN',
                header: 'Tahun',
                width: 90,
                hidden:true
            }, {
                dataIndex: 'DATA_COMPANY',
                header: 'Company',
                width: 90,
                hidden:true
            }, {
                dataIndex: 'DATA_BARCODE',
                header: 'Barcode ID',
                width: 90,
                hidden:true
            }, {
                dataIndex: 'DATA_ORID',
                header: 'Organization ID',
                width: 90,
                hidden:true
            }],
            listeners: {
                dblclick: {
                    element: 'body',
                    fn: function() {
                        Ext.getCmp('tf_asset').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_ASSET'));
                        Ext.getCmp('tf_group').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_GROUP'));
                        Ext.getCmp('tf_area').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_AREA'));
                        Ext.getCmp('tf_plat').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_PLAT'));
                        Ext.getCmp('tf_lambung').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_LAMBUNG'));
                        Ext.getCmp('tf_model').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_MODEL'));
                        Ext.getCmp('tf_type').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_TYPE'));
                        Ext.getCmp('tf_bulan').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_BULAN'));
                        Ext.getCmp('tf_tahun').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_TAHUN'));
                        Ext.getCmp('tf_company').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_COMPANY'));
                        Ext.getCmp('tf_barcode').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_BARCODE'));
                        Ext.getCmp('tf_orid').setValue(grid_asset.getSelectionModel().getSelection()[0].get('DATA_ORID'));
                        wind.hide();
                    }
                }
            }
        });

        var wind = Ext.create('Ext.Window', {
            width: 665,
            height: 350,
            layout: 'fit',
            closeAction: 'hide',
            tbar:[,{
                xtype:'label',
                html:'&nbsp',
            },{
                xtype:'textfield',
                fieldLabel:'No. Lambung',
                id:'tf_filter1',
                labelWidth:75,
                listeners: {
                     change: function(field,newValue,oldValue){
                            field.setValue(newValue.toUpperCase());
                    }
                }
            },{
                xtype:'label',
                html:'&nbsp',
            }, {
                xtype: 'label',
                html: '&nbsp'
            },{
                xtype:'textfield',
                fieldLabel:'Area',
                id:'tf_filterArea',
                labelWidth:40,
                listeners: {
                     change: function(field,newValue,oldValue){
                            field.setValue(newValue.toUpperCase());
                    }
                }
            }, {
                xtype: 'label',
                html: '&nbsp'
            }, {
                xtype: 'label',
                html: '&nbsp'
            }, {
                xtype: 'label',
                html: '&nbsp'
            },{
                xtype:'button',
                text:'Cari',
                scale:'small',
                width: 45,
                border: 1,
                style: {
                    borderColor: 'black',
                    borderStyle: 'solid'
                },
                cls:'button-popup',
                handler:function(){
                    //if ((Ext.getCmp('tf_filter1').getValue() != '')){
                        maskgrid = new Ext.LoadMask(Ext.getCmp('grid_asset_id'), {msg: "Memuat . . ."});
                        maskgrid.show();
                        store_asset.load({
                            params:{
                                nolambung:Ext.getCmp('tf_filter1').getValue(),
                                area:Ext.getCmp('tf_filterArea').getValue()
                            },
                            timeout:50000000000,
                        });
                    //}
                    //else {
                        //alertDialog('Kesalahan','Masukkan No Lambung atau No Plat.');
                    //}
                    //Ext.getCmp('tf_filter1').setValue('');
                    //Ext.getCmp('tf_filter2').setValue('');
                }
            }, {
                xtype: 'label',
                html: '&nbsp'
            },{
                xtype:'button',
                text:'Reset',
                scale:'small',
                width: 45,
                border: 1,
                style: {
                    borderColor: 'black',
                    borderStyle: 'solid'
                },
                cls:'button-popup',
                handler:function(){                 
                    maskgrid = new Ext.LoadMask(Ext.getCmp('grid_asset_id'), {msg: "Memuat . . ."});
                    maskgrid.show();
                    Ext.getCmp('tf_filter1').setValue('');
                    Ext.getCmp('tf_filterArea').setValue('');
                    store_asset.load({
                        params:{
                            nolambung:"",
                            area:""
                        }
                    });
                }
            }],
            items: grid_asset
        });
        
        var contentPanel = Ext.create('Ext.panel.Panel', {
            bodyStyle: 'spacing: 10px;border:none',
            items: [{
                xtype: 'label',
                html: '<div align="center"><font size="5"><font face="Arial"><b>Master Barcode</b></font></div>'
            }, {
                xtype: 'label',
                html: '&nbsp'
            }, {
                xtype: 'label',
                html: '&nbsp<br><br>'
            }, {
                xtype: 'panel',
                bodyStyle: 'padding-top: 5px;padding-bottom: 10px;border:none',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: 'userid',
                    width: 350,
                    labelWidth: 100,
                    id: 'hd_id',
                    hidden: true
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'formtype',
                    width: 350,
                    labelWidth: 100,
                    id: 'tf_formtype',
                    value: 'tambah',
                    hidden: true
                }, {
                    layout: 'column',
                    border: false,
                    items: [{
                        columnWidth: .36,
                        border: false,
                        layout: 'anchor',
                        defaultType: 'textfield',
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'No. Asset',
                            width: 298,
                            labelWidth: 85,
                            id: 'tf_asset',
                            //readOnly: true
                        }]
                    }, {
                        columnWidth: .4,
                        border: false,
                        layout: 'anchor',
                        defaultType: 'textfield',
                        items: [{
                            xtype: 'button',
                            iconCls: 'caributton',
                            scale: 'small',
                            width: 20,
                            handler: function() {
                                wind.show();
                                maskgrid = new Ext.LoadMask(Ext.getCmp('grid_asset_id'), {
                                    msg: "Memuat . . ."
                                });
                                maskgrid.show();
                                store_asset.load();
                            }
                        }]
                    }]
                },{
                        xtype: 'label',
                        html: '<br/>',
                },{
                        xtype: 'label',
                        html: '<b>Keterangan No. Asset</b>'
                },{
                        xtype: 'label',
                        html: '<br/><br/>',
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Company ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_company',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Group ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_group',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Area ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_area',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Merk ',
                        width: 200,
                        labelWidth: 81,
                        id: 'tf_type',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Model ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_model',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'No Plat ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_plat',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'No Lambung ',
                        width: 250,
                        labelWidth: 81,
                        id: 'tf_lambung',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Bulan ',
                        width: 200,
                        labelWidth: 81,
                        id: 'tf_bulan',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Tahun ',
                        width: 200,
                        labelWidth: 81,
                        id: 'tf_tahun',
                        fieldStyle: 'background:#F0FFFF;',
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Barcode ID ',
                        width: 200,
                        labelWidth: 81,
                        id: 'tf_barcode',
                        fieldStyle: 'background:#F0FFFF;',
                        hidden: true,
                        readOnly: true
                },{
                        xtype: 'textfield',
                        fieldLabel: 'Organization ID ',
                        width: 200,
                        labelWidth: 81,
                        id: 'tf_orid',
                        fieldStyle: 'background:#F0FFFF;',
                        hidden: true,
                        readOnly: true
                },{
                        xtype: 'label',
                        html: '<br/>',
                },{
                    xtype: 'panel',
                    bodyStyle: 'padding-left: 100px;padding-bottom: 50px;border:none',
                    items: [{
                        xtype: 'button',
                        text: 'Generate Barcode',
                        width: 100,
                        handler: function() {
                            if (Ext.getCmp('tf_asset').getValue() != '') {
                                // if (Ext.getCmp('tf_imei').getValue() != '') {
                                    // if (Ext.getCmp('tf_imei').getValue() != '') {
                                        // if (Ext.getCmp('cb_hp').getValue() != '') {
                                            // if (Ext.getCmp('cb_owner').getValue() != '') {
                                                // if (Ext.getCmp('cb_driver').getValue() != '') {
                                                    // if (Ext.getCmp('cb_timezone').getValue() != '') {
                                                        Ext.Ajax.request({
                                                            url: '<?php echo 'barcode/barcode.php '; ?>',
                                                            params: {
                                                                asset: Ext.getCmp('tf_asset').getValue(),
                                                                plat: Ext.getCmp('tf_plat').getValue(),
                                                                lambung: Ext.getCmp('tf_lambung').getValue(),
                                                                barcode: Ext.getCmp('tf_barcode').getValue(), // barcode adalah instance id
                                                                orid: Ext.getCmp('tf_orid').getValue(),
                                                            },
                                                            method: 'POST',
                                                            success: function(response) {
                                                                var json = Ext.decode(response.responseText);
                                                                if (json.rows == "sukses") {
                                                                    alertDialog('Sukses', 'Data berhasil disimpan.');
                                                                    Ext.getCmp('tf_asset').setValue('');
                                                                    Ext.getCmp('tf_plat').setValue('');
                                                                    Ext.getCmp('tf_lambung').setValue('');
                                                                    Ext.getCmp('tf_barcode').setValue('');
                                                                    Ext.getCmp('tf_orid').setValue('');
                                                                } else {
                                                                    alertDialog('Kesalahan', "Data gagal disimpan. " + json.results);
                                                                }
                                                            },
                                                            failure: function(error) {
                                                                alertDialog('Kesalahan', 'Data gagal disimpan');
                                                            }
                                                        });
                                                    // } else {
                                                        // alertDialog('Kesalahan', 'Timezone belum diisi.');
                                                    // }
                                                // } else {
                                                    // alertDialog('Kesalahan', 'Driver belum diisi.');
                                                // }
                                            // } else {
                                                // alertDialog('Kesalahan', 'Penanggung jawab belum diisi.');
                                            // }
                                        // } else {
                                            // alertDialog('Kesalahan', 'No. HP belum diisi.');
                                        // }
                                    // } else {
                                        // alertDialog('Kesalahan', 'No. Imei belum diisi.');
                                    // }
                                // } else {
                                    // alertDialog('Kesalahan', 'No. Serial belum diisi.');
                                // }
                            } else {
                                alertDialog('Kesalahan', 'No. Asset belum diisi.');
                            }
                        }
                    }, {
                        xtype: 'label',
                        html: '&nbsp',
                    }, {
                        xtype: 'button',
                        text: 'Clear',
                        width: 50,
                        handler: function() {
                            //bar128("asdasd");
                            Ext.getCmp('tf_asset').setValue('');
                            Ext.getCmp('tf_company').setValue('');
                            Ext.getCmp('tf_group').setValue('');
                            Ext.getCmp('tf_area').setValue('');
                            Ext.getCmp('tf_plat').setValue('');
                            Ext.getCmp('tf_lambung').setValue('');
                            Ext.getCmp('tf_bulan').setValue('');
                            Ext.getCmp('tf_tahun').setValue('');
                            Ext.getCmp('tf_model').setValue('');
                            Ext.getCmp('tf_type').setValue('');
                            Ext.getCmp('tf_formtype').setValue('tambah');
                            Ext.getCmp('hd_id').setValue('');
                        }
                    }, {
                        xtype: 'label',
                        html: '&nbsp',
                    }]
                }]
            },],
        });
        contentPanel.render('content');
    }
</script>
<div id="wrapper">
    <div id="headerlama">
        <div id="menu">
            <?php include '../main/menu.php'; ?>
        </div>
    </div>
    <div id="logo"></div>
    <div id="page">
        <div id="content">
        </div>
        <div style="clear: both;">&nbsp;</div>
    </div>
    <?php 
    include '../main/footer.php'; 
    } else {
        header("location: http://192.168.0.40/gps/index.php");
    }
    ?>