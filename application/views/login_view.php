<div class="page_center">
    <div class="box ">
        <div class="title">
            Вход в систему
        </div>
        <div class="content" align="center">
		
		<?php
			if (isset($data['error'])) 
			{
				echo "<div class=\"message error\">"; 
				echo $data['error'];
				echo '</div>';
			}
		?>			
              <form id="form" action="" method="post" name="auth_form">
                <table width="70%" border="0">
                  <tr>
                    <td colspan="2" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      
                      <tr>
					  <br />
                        <td align="right" nowrap="nowrap"><span class="label">Имя пользователя</span></td>
                        <td align="center" style="padding: 5px;">
							<input style="width: 200px" type="text" id="username" name="login" required="required" maxlength="15"/>						</td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap"><span class="label">Пароль</span></td>
                        <td align="center" style="padding: 5px;">
							<input style="width: 200px" type="password" id="password" name="password" required="required" maxlength="30"/>						</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td align="right" class=" label note"><a href="#">Забыли пароль?</a></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="50%" align="left">
                      	<ul><li><a href="registration.html"> Регистрация</a></li></ul>
                     </td>
                    <td width="50%" align="right">
					<div class="button active" onclick="document.auth_form.submit()" style="cursor:pointer">Вход</div>
				</td>
                  </tr>
                </table>
              </form>
        </div>
    </div>
</div>





