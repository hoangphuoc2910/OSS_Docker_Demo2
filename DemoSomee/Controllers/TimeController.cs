using Microsoft.AspNetCore.Mvc;
using System.Data.SqlClient;

namespace DemoSomee.Controllers
{
    public class VisitInput
    {
        public string Name { get; set; }
        public DateTime? Dob { get; set; }
        public int Rating { get; set; }
    }

    [ApiController]
    [Route("api/[controller]")]
    public class TimeController : ControllerBase
    {
        // THAY CHUỖI KẾT NỐI CỦA BẠN VÀO ĐÂY
        private readonly string connectionString = "workstation id=DemoDBSomee.mssql.somee.com;packet size=4096;user id=hoangphuoc_SQLLogin_1;pwd=l7fgqcxbx1;data source=DemoDBSomee.mssql.somee.com;persist security info=False;initial catalog=DemoDBSomee;TrustServerCertificate=True";

        [HttpGet]
        public IActionResult CheckStatus()
        {
            try {
                int totalVisits = 0;
                using (SqlConnection conn = new SqlConnection(connectionString)) {
                    conn.Open();
                    using (SqlCommand cmd = new SqlCommand("SELECT COUNT(*) FROM VisitLogs", conn)) {
                        totalVisits = (int)cmd.ExecuteScalar();
                    }
                }
                return Ok(new { serverTime = DateTime.Now.ToString("dd/MM/yyyy HH:mm:ss"), totalChecks = totalVisits });
            } catch (Exception ex) { return StatusCode(500, new { message = ex.Message }); }
        }

        [HttpPost]
        public IActionResult SubmitRating([FromBody] VisitInput input)
        {
            try {
                using (SqlConnection conn = new SqlConnection(connectionString)) {
                    conn.Open();
                    string query = "INSERT INTO VisitLogs (VisitTime, UserAgent, VisitorName, VisitorDOB, Rating) VALUES (@time, @agent, @name, @dob, @rating)";
                    using (SqlCommand cmd = new SqlCommand(query, conn)) {
                        cmd.Parameters.AddWithValue("@time", DateTime.Now);
                        cmd.Parameters.AddWithValue("@agent", Request.Headers["User-Agent"].ToString());
                        cmd.Parameters.AddWithValue("@name", input.Name ?? (object)DBNull.Value);
                        cmd.Parameters.AddWithValue("@dob", input.Dob.HasValue ? (object)input.Dob.Value : DBNull.Value);
                        cmd.Parameters.AddWithValue("@rating", input.Rating);
                        cmd.ExecuteNonQuery();
                    }
                }
                return Ok(new { success = true, data = new { name = input.Name, rating = input.Rating, time = DateTime.Now.ToString("HH:mm:ss") } });
            } catch (Exception ex) { return StatusCode(500, new { message = ex.Message }); }
        }
    }
}