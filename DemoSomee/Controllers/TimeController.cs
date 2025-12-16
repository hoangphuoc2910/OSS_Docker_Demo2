using Microsoft.AspNetCore.Mvc;
using System.Data.SqlClient;

namespace DemoSomee.Controllers
{
    // Class để hứng dữ liệu từ Frontend gửi lên
    public class VisitInput
    {
        public string Name { get; set; }
        public DateTime? Dob { get; set; } // Ngày sinh
        public int Rating { get; set; }    // Số sao (1-5)
    }

    [ApiController]
    [Route("api/[controller]")]
    public class TimeController : ControllerBase
    {
        // THAY CHUỖI KẾT NỐI CỦA BẠN VÀO ĐÂY
        private readonly string connectionString = "workstation id=...;user id=...;pwd=...;...";

        // Đổi từ [HttpGet] sang [HttpPost] vì chúng ta đang GỬI dữ liệu lên
        [HttpPost]
        public IActionResult CheckTimeAndRate([FromBody] VisitInput input)
        {
            int totalVisits = 0;
            try
            {
                using (SqlConnection conn = new SqlConnection(connectionString))
                {
                    conn.Open();

                    // 1. Lưu thông tin cá nhân và đánh giá vào Database
                    string insertQuery = @"
                        INSERT INTO VisitLogs (VisitTime, UserAgent, VisitorName, VisitorDOB, Rating) 
                        VALUES (@time, @agent, @name, @dob, @rating)";

                    using (SqlCommand cmd = new SqlCommand(insertQuery, conn))
                    {
                        cmd.Parameters.AddWithValue("@time", DateTime.Now);
                        
                        // Lấy User-Agent
                        string agent = Request.Headers.ContainsKey("User-Agent") 
                                       ? Request.Headers["User-Agent"].ToString() : "Unknown";
                        cmd.Parameters.AddWithValue("@agent", agent);

                        // Các trường mới
                        cmd.Parameters.AddWithValue("@name", string.IsNullOrEmpty(input.Name) ? (object)DBNull.Value : input.Name);
                        cmd.Parameters.AddWithValue("@dob", input.Dob.HasValue ? (object)input.Dob.Value : DBNull.Value);
                        cmd.Parameters.AddWithValue("@rating", input.Rating);

                        cmd.ExecuteNonQuery();
                    }

                    // 2. Đếm lại tổng số lượt
                    string countQuery = "SELECT COUNT(*) FROM VisitLogs";
                    using (SqlCommand cmd = new SqlCommand(countQuery, conn))
                    {
                        totalVisits = (int)cmd.ExecuteScalar();
                    }
                }

                // 3. Trả về kết quả
                return Ok(new 
                { 
                    message = $"Cảm ơn {input.Name} đã đánh giá {input.Rating} sao!", 
                    serverTime = DateTime.Now.ToString("dd/MM/yyyy HH:mm:ss"),
                    totalChecks = totalVisits 
                });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Lỗi Server: " + ex.Message });
            }
        }
    }
}