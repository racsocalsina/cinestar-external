<?php

return [
    'error'         => [
        'not_found'            => 'Dirección inválida',
        'unprocessable_entity' => 'Datos inválidos',
        'unauthorized'         => 'No autenticado',
        'forbidden'            => 'No autorizado'
    ],
    'middleware'    => [
        'laratrust' => [
            'not_permissions' => 'El usuario no tiene permisos necesarios.'
        ]
    ],
    'sent_message'  => '¡Se ha enviado satisfactoriamente tu mensaje! Pronto nos contactaremos contigo',
    'roles'         => [
        'super_admin_cannot_be_update' => 'EL rol super-admin no se puede editar',
        'super_admin_cannot_be_delete' => 'EL rol super-admin no se puede eliminar',
        'permissions_dont_exist'       => 'Los permisos no existen',
        'permission_does_not_exist'    => 'El permiso :name no existe',
        'delete_admins_related'        => 'No se puede eliminar porque existen usuarios con este rol.'
    ],
    'admins'        => [
        'delete_at_least_one_super_admin_must_exist' => 'No se puede eliminar este usuario ya que es el unico de tipo super admin en el sistema.',
        'update_at_least_one_super_admin_must_exist' => 'No se puede actualizar el rol de este usuario ya que es el unico de tipo super admin en el sistema.',
        'update_cannot_disable_unique_super_admin'   => 'No se puede deshabilitar este usuario ya que es el unico de tipo super admin en el sistema.',
        'cannot_delete_super_admin'                  => 'No se puede eliminar este usuario de tipo super admin.',
        'cannot_update_super_admin'                  => 'No se puede actualizar este usuario de tipo super admin.',
        'headquarter_id_is_required_for_role'        => 'El cine es requerido para este rol seleccionado.'
    ],
    'movie_genders' => [
        'delete_movies_related' => 'No se puede eliminar porque existen peliculas con este género.'
    ],
    'headquarters'  => [
        'movie_formats_do_not_exist' => 'Tipos de sala no existen.',
        'no_images_uploaded'         => 'No hay imagenes, al menos una imagen es requerida.',
        'image_mime_type_not_valid'  => 'El archivo :name no es una imagen valida.',
        'image_file_size_exceeded'   => 'La imagen :name se ha excedido de los :size permitidos.',
        'images_limit_exceeded'      => 'La sede solo puede tener un maximo de :limit imágenes.',
    ],
    'purchases'     => [
        'already_confirmed'                       => 'Esta compra ya esta con estado confirmado.',
        'invalid_code'                            => 'EL código enviado no es valido.',
        'not_exist'                               => 'Compra no existe.',
        'payment_gateway_error'                   => 'Se ha producido un error con la pasarela de pago, por favor contacte con el administrador para mas detalles.',
        'fe_error'                                => 'Se ha producido un error con el facturador.',
        'data_not_match_already_purchase_created' => ':field no coincide con la compra ya creada.',
        'remote_key_does_not_exist'               => 'Código inválido',
        'awards_not_allowed_for_guest'            => 'El canje de premios solo es permitido para usuarios autenticados',
        'not_valid_for_cancelling'                => 'Solo compras con estado completado pueden ser canceladas.'
    ],
    'internal_app'  => [
        'no_connection' => 'No se puede conectar con la sede.',
    ],
    'cities'        => [
        'delete_headquarters_related' => 'Esta ciudad no se puede eliminar porque existen sedes relacionadas.',
        'unique_name_by_trade_name'   => 'EL nombre de la ciudad ya existe para el nombre comercial :trade_name'
    ],
    'sweets'        => [
        'not_exist_with_id' => ':entity con id :id no existe',
        'no_stock'          => ':name no tiene stock disponible',
        'no_stock_insufficient'          => ':name no cuenta con suficiente stock'
    ],
    'cards'         => [
        'not_owner'            => 'Esta tarjeta no te pertenece.',
        'not_exist'            => 'Tarjeta no existe.',
        'token_already_exists' => 'Tarjeta y fecha de vencimiento ya existen.',
    ],
    'points'        => [
        'insufficient_points' => 'Puntos insuficientes'
    ],
    'commons'       => [
        'failed'            => 'Por favor vuelve a intentarlo',
        'not-found'         => 'No se encontro',
        'un-authorized'     => 'No puedes entrar aqui',
        'not-toggle-status' => 'No se puede activar o inactivar'
    ]
];

