<?php

    // ���̃f�B���N�g���̃t���p�X�擾����
    $hd = realpath(dirname(__FILE__));


    // �z�[���f�B���N�g����HOME_DIR�Ƃ���
    define(HOME_DIR, $hd);


    // Smarty�N���X�̂���f�B���N�g����CLASS_DIR�Ƃ���
    define(CMN_DIR, $hd."/cmn/");


    // View�N���X�̂���f�B���N�g����VIEWCLASS_DIR�Ƃ���
    define(SQL_DIR, $hd."/sql/");
?>