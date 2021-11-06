<?php defined('DOMAIN') or exit(header('Location: /'));?>

<div class="card">
                                    <div class="card-body">
                
                                        <h3 class="text-center mt-0 m-b-15">
                                            <a href="#" class="logo logo-admin"><img src="<?=DOMAIN?>/img/logo3.png" alt="logo" height="45"></a>
                                        </h3>
                
                                        <h4 class="text-muted text-center font-18"><b>Авторизация</b></h4>
                
                                        <div class="p-2">
                                            <form class="form-horizontal m-t-20" action="" method="post" id="form_getLogin">
                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <input class="form-control" type="text" name="username@" required="" placeholder="Login">
                                                    </div>
                                                </div>
                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <input class="form-control" type="password" name="pass@" required="" placeholder="************">
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="module" value="login" />
                                                <input type="hidden" name="component" value="" />
                                                <input type="hidden" name="url" value="<?=$_POST['url'];?>" />
                                                <div class="form-group text-center row m-t-20">
                                                    <div class="col-12">
                                                        <button id="getLogin" style="background-color: #EB1D37;" class="send_form btn btn-primary btn-block waves-effect waves-light" type="submit">
                                                          Войти
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                
                                    </div>
                                </div>