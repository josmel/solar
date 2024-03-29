yOSON.require={
    "#frm-article":{
        rules:{
            "name":{
                required:true,
            },
            "cell":{
                required:true
            },
            "imageIcon":{
                required:true,
                extension: "png|jpe?g|gif"
            },
            "imageMedium":{
                required:true,
                extension: "png|jpe?g|gif"
            },
            "imageLarge":{
                required:true,
                extension: "png|jpe?g|gif"
            }
        },
        messages:{
            "name":{
                required:"Ingrese su Nombre"
            },
            "cell":{
                required:"Seleccione un celda"
            },
            "imageIcon":{
                required:"Ingrese la imagen para el ícono",
                extension: "Sólo se permiten extensiones PNG, JPG, JPEG, GIF"
            },
            "imageMedium":{
                required:"Ingrese la imagen mediana",
                extension: "Sólo se permiten extensiones PNG, JPG, JPEG, GIF"
            },
            "imageLarge":{
                required:"Ingrese la imagen larga",
                extension: "Sólo se permiten extensiones PNG, JPG, JPEG, GIF"
            }
        }
    },
    "#frm-login":{
         rules:{
            "txtUsername":{
                required:true
            },
            "txtPassword":{
                required:true
            }
        },
        messages:{
            "txtUsername":{
                required:"Ingrese su usuario"
            },
            "txtPassword":{
                required:"Ingrese su contraseña"
            }
        }
    },
    "#frm-video":{
        rules:{
            "title":{
                required:true
            },
            "link":{
                required:true
            }
        },
        messages:{
            "title":{
                required:"El título del video es requerido"
            },
            "link":{
                required:"El link del video es requerido"
            }
        }
        
    }
};