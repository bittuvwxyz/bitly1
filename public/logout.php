<?php require_once __DIR__.'/../includes/bootstrap.php'; logout_user(); start_secure_session(); flash('success','You have been logged out.'); redirect('/');
