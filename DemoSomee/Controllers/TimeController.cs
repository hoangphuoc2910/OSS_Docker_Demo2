using Microsoft.AspNetCore.Mvc;
using System;

namespace DemoSomee.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class TimeController : ControllerBase
    {
        [HttpGet]
        public IActionResult GetServerTime()
        {
            // Back-end xử lý: Lấy giờ hiện tại của Server và trả về dạng JSON
            var data = new 
            { 
                Message = "Kết nối thành công tới Somee!", 
                ServerTime = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"),
                Location = "Server Somee"
            };
            return Ok(data);
        }
    }
}