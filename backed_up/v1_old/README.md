# NAMSAI api documentaion
version: 1

//TODO: Need to implement
- check token date issue and make blacklist after change password
- story order change
- edge order change
## GLOBAL ERROR message
ERROR:
  -
    response_code: 404
    code: 404
    message: invalid route
  -
    response_code: 500
    code: 500
    description: internal server error
  -
    response_code: 401
    code: 401
    message: access_token is invalid
  - response_code: 400
    code: 400
    message: access_token is require

## Authentication

### login
POST: /v1/auth
PARAMETER: email,password
RETURN: access_token,
ERROR:
  -
    response_code: 401
    code: 1
    message: username,email or password isn\'t correct
  -
    response_code: 400
    code: 2
    message: require parameter username or email and password

## User

### สมัครสมาชิกใหม่
POST: /v1/users
PARAMETER: email,username,password,name
RETURN: (user) id
ERROR:
  -
    response_code: 400
    code: 4
    message: parameter name,email,username and password is require for register
  -
    response_code: 400
    code: 5
    message: this email has been already register
  -
    response_code: 400
    code: 6
    message: this username has been already register
  -
    response_code: 400
    code: 7
    message: username {{username}} is reserved for system

### เปลี่ยนพาสเวิร์ด
POST: /v1/users/password
PARAMETER: password,newpassword,access_token
RESPONSE: (user)id
ERROR:
  -
    response_code: 400
    code: 8
    message: password is mismatch

### เปลี่ยนอีเมล
POST: /v1/users/email
PARAMETER: email,access_token
RESPONSE: (user)id
ERROR:
  -
    response_code: 400
    code: 5
    message: this email has been already register
  -
    response_code: 400
    code: 9
    message: email is invalid


### เปลี่ยนชื่อเต็ม
POST: /v1/users/name
PARAMETER: name,access_token
RESPONSE: (user)id
ERROR:

### เปลี่ยนคำอธิบาย
POST: /v1/users/bio
PARAMETER: bio

### แสดงข้อมูลผู้ใช้ของฉัน
GET: /v1/users
PARAMETER:
  - access_token
RESPONSE:
  - id
  - public_repos (นับจำนวน repo ที่มี)

### แสดงข้อมูลของผู้ใช้คนที่กำหนด
GET: /v1/users/:username
PARAMETER:
RESPONSE:
  - id
  - name
  - username
  - email
  - bio
  - public_repos (นับจำนวน repo ที่มี)
ERROR:
  -
    response_code: 404
    code: 3
    message: username {{:username}} not exist,

### แสดง repos ของผู้ใช้ที่กำหนด
GET: /v1/users/:username/repos


## Repo

### สร้าง REPO ใหม่
POST: /v1/repos/:user/:repo/
PARAMETER:
  - access_token
  - name
  - description
  - private (boolean)
RESPONSE:
  - id (repo's id)
ERROR:
  -
    response_code: 400
    code: 10
    message: require parameter access_token,name and description
  -
    response_code: 400
    code: 11
    message: name must contain english alphabelt and number only
  -
    response_code: 400
    code: 12
    message: respository name {{name}} isn't avaliable

### เข้าถึง REPO
GET: /v1/repos/:user/:repo/
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - name
  - description
  - private (boolean)
ERROR:
  -
    response_code: 404
    code: 13
    message: respository {{user}}/{{name}} isn't found

### ลบ REPO
DELETE: /v1/repos/:user/:repo/
PARAMETER:
  - access_token
RESPONSE:
  - id (repo's id)
ERROR:
  -
    response_code: 401
    code: 14
    message: only owner can delete repository

### อ่านค่า private ของ repo
ดูว่า repo ปัจจุบันเป็น Private อยู่หรือไม่
GET: /repos/:user/:repo/private
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - private (boolean)

### ตั้ง Private ของ repo
POST: /repos/:user/:repo/private
PARAMETER:
  - access_token
RESPONSE:
  - id (repo's id)
Error
  -
    response_code: 401
    code: 15
    message: only owner can set private state

### อ่านรายละเอียดของ repo
GET: /repos/:user/:repo/description
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - description

### เปลี่ยนรายละเอียดของ repo
POST: /repos/:user/:repo/description
PARAMETER:
  - access_token
  - description
RESPONSE:
  - id (repo's id)
ERROR:
  -
    response_code: 401
    code: 15
    message: only owner can set description
  -
    response_code: 400
    code: 16
    message: require parameter access_token and description

#เพิ่มผู้ร่วมพัฒนา
POST: /repos/:user/:repo/contributor
PARAMETER:
  - username
  - access_token (owner only)
RESPONSE:
  - id (repo's id)
ERROR:
  -
    response_code: 400
    code: 17
    message: require parameter access_token and username
  -
    response_code: 401
    code: 18
    message: only owner can add contributor
  -
    response_code: 400
    code: 19
    message: {{user}} has been already add to {{username}}/{{reponame}}
  -
    response_code: 404
    code: 22
    message: username {{user}} isn't exist
##ดึงข้อมูลผู้ร่วมพัฒนาทั้งหมด
GET: /repos/:user/:repo/contributor
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - contributor (array){id,username,name,email,bio}
##ลบผู้ร่วมพัฒนา
DELETE: /repos/:user/:repo/contributor
PARAMETER:
  - username
  - access_token (owner only)
RESPONSE:
  - id (repo's id)
ERROR:
  -
    response_code: 401
    code: 20
    message: only owner can remove contributor
  -
    response_code: 400
    code: 21
    message: {{user}} isn't contribute in {{username}}/{{reponame}}

###สร้างโหนดใหม่
POST: /repos/:user/:repo/nodes
PARAMETER:
  - access_token
  - story_id
  - type
  - value
RESPONSE:
  - id (node's id)
ERROR:
  -
    response_code: 400
    code: 23
    message: storyid,type and access_token are require

###เปลี่ยนค่าโหนดเดิม
POST: /repos/:user/:repo/node/:id
PARAMETER:
  - access_token
  - value
RESPONSE:
  - id (node's id)
ERROR:
  -
    response_code: 400
    code: 24
    message: value and access_token are require
###ลบโหนด
DELETE: /repos/:user/:repo/nodes/:id
PARAMETER:
  - access_token
RESPONSE:
  - id (node's id)
ERROR:
  -
    response_code: 400
    code: 25
    message: access_token is require
  -
    response_code: 400
    code: 26
    message: node_id {{node_id}} isn't exist

###ดึงค่าของโหนด
GET: /repos/:user/:repo/node/:id
PARAMETER:
  - access_token (optional for private repo)
RESPONSE
  - id
  - type
  - value
  - story {id,name}

###สร้างหัวข้อเรื่องใหม่
POST: /repos/:user/:repo/stories
PARAMETER:
  - name (optional)
  - access_token
RESPONSE:
  - id (story's id)
ERROR:
  -
    response_code: 400
    code: 33
    message: repo_id {{repoId}} already has order number {{order}}

###ลบหัวข้อเรื่อง
DELETE: /repos/:user/:repo/stories/id
PARAMETER:
  - access_token
RESPONSE:
  - id (story's id)
ERROR:
  -
    response_code: 400
    code: 27
    message: story_id {{story_id}} isn't exist

###รับหัวข้อเรื่อง
GET: /repos/:user/:repo/stories/:id
PARAMETER:
  - access_token (optional for private repo)
RESPONSE:
  - id (story's id)
  - name

###เปลี่ยนชื่อหัวข้อเรื่อง
POST: /repos/:user/:repo/stories/:id
PARAMETER:
  - access_token
  - name
RESPONSE:
  - id (node's id)
ERROR:
  -
    response_code: 400
    code: 28
    message: name and access_token are require

###สร้างเส้นเชื่อมใหม่ระหว่าง 2 Node
POST /repos/:user/:repo/edges
PARAMETER:
  - access_token
  - current (current node id)
  - next (node that point next)
  - order (Edge order)(optional)
RESPONSE:
  - id (edge's id)
ERROR:
  -
    response_code: 400
    code: 29
    message: current, next and access_token are require
  -
    response_code: 400
    code: 30
    message: Edge from {{current}} to {{next}} is already exist
  -
    response_code: 400
    code: 32
    message: node_id {{current}} already has order number {{order}}
### ลบเส้นเชื่อม
DELETE: /repos/:user/:repo/edges
PARAMETER:
  - current
  - next
  - access_token (optional for private repo)
RESPONSE:
  - id (Edge id)
ERROR:
  -
    response_code: 400
    code: 31
    message: Edge from {{current}} to {{next}} isn't exist

###รับเส้นเชื่อม
GET: /repos/:user/:repo/edge/:nodeid
PARAMETER:
  - access_token (optional for private repo)
RESPONSE:
  - id (node_id)
  - next [id,current,next,order]

###ร้บหัวข้อเรื่อง
GET: /repos/:user/:repo/stories
PARAMETER:
  - access_token (optional for private repo)
RESPONSE:
  - id (repo's id)
  - stories (id (story's id),name)
