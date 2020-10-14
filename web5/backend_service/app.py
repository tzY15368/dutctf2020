from flask import Flask,session,request,jsonify
import time,json,base64,random
from io import BytesIO
from  PIL import Image,ImageDraw,ImageFont,ImageFilter
app = Flask(__name__)
msg = {"success":False,"msg":""}
app.secret_key = 'brrrrrrrrrrrrrrrrrrrrsecret_key'
flag = r'dutctf{faster_faster}'
white = (255,255,255)
black = (0,0,0)
def getColor():
    return (random.randint(64, 255), random.randint(64, 255), random.randint(64, 255))
def getColor2():
    return (random.randint(32, 127), random.randint(32, 127), random.randint(32, 127))
def getcaptcha(len:int=6)->str:
    return ''.join(random.sample(['2','3','4','5','6','7','8','9','z','y','x','w','t','s','r','q','p','n','m','k','j','i','h','g','f','e','d','c','b','a'], len))
def img_to_base64():
    cpt = getcaptcha()
    width = 60 * 6
    height = 60
    #  生成图片
    image = Image.new('RGB', (width, height), (255, 255, 255))
    # 创建Font对象:
    font = ImageFont.truetype('Arial.ttf', 36)
    # 创建Draw对象:
    draw = ImageDraw.Draw(image)
    # 填充每个像素:
    for x in range(width):
        for y in range(height):
            #draw.point((x, y), fill=getColor())
            draw.point((x,y),fill=white)#固定白色底色
    # 输出文字:
    listChar=[]
    for t in range(6):
        char=cpt[t]
        listChar.append(char)
        #draw.text((60 * t + 10, 10), char, font=font, fill=getColor2())
        draw.text((60 * t + 10, 10), char, font=font, fill=black)
    # 模糊:
    #image = image.filter(ImageFilter.BLUR)
    #image.save('code.jpg', 'jpeg');
    image.show()
    out_buffer = BytesIO()
    image.save(out_buffer,format='jpeg')
    byte_data = out_buffer.getvalue()
    base64_str = base64.b64encode(byte_data)
    return cpt,base64_str
"""
    with open("C:\\Users\\user\\Desktop\\20170508134213.png","rb") as f:
        # b64encode是编码，b64decode是解码
        base64_data = base64.b64encode(f.read())
        # base64.b64decode(base64data)
        return base64_data"""
def get_captcha_and_img():
    #img = r"data:image/png;base64,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"
    #captcha = "placeholder"
    captcha,img = img_to_base64()
    img = b"data:image/jpeg;base64,"+img
    return captcha,str(img,encoding='utf-8')

@app.route('/web5/captcha',methods=['GET'])
def home():
    #msg = request.values.get('msg')#200*50的码
    cpt,img = get_captcha_and_img()
    #print(cpt)
    #print(type(img))
    session['captcha'] = cpt
    session['created_at']=time.time()
    return img
@app.route('/web5/captcha',methods=['POST'])
def homepost():
    data = request.get_data()
    try:
        jdata = json.loads(data)
    except:
        cpt,img = get_captcha_and_img()
        session['captcha'] = cpt
        session['created_at']=time.time()
        msg['msg']="bad json";msg['cpt']=img
        return jsonify(msg)
    if 'captcha' not in session:
        cpt,img = get_captcha_and_img()
        session['captcha'] = cpt
        session['created_at']=time.time()
        msg['msg']="no session!";msg['cpt']=img
        return jsonify(msg)
    cpt = jdata.get('captcha')
    if cpt== None:
        cpt,img = get_captcha_and_img()
        session['captcha'] = cpt
        session['created_at']=time.time()
        msg['msg'] = "码呢？";msg['cpt']=img
        return jsonify(msg)
        
    if cpt != session['captcha']:
        cpt,img = get_captcha_and_img()
        session['captcha'] = cpt
        session['created_at']=time.time()
        msg['msg']='码错了！';msg['cpt']=img
        return jsonify(msg)
    if time.time()-session['created_at']>=2:
        cpt,img = get_captcha_and_img()
        session['captcha'] = cpt
        session['created_at']=time.time()
        msg['msg']="太慢了！";msg['cpt']=img
        return jsonify(msg)
    else:
        msg['success']=True;msg['msg']=flag
        return jsonify(msg)

if __name__ == "__main__":
    app.run('127.0.0.1',port=8840,debug=True)
