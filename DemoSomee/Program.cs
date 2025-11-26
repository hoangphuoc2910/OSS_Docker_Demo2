var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllers();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseDeveloperExceptionPage();
}

app.UseHttpsRedirection();

// --- BẮT ĐẦU ĐOẠN QUAN TRỌNG ---
// Cho phép chạy file tĩnh (html, css, js)
app.UseDefaultFiles(); 
app.UseStaticFiles();
// ------------------------------

app.UseAuthorization();

app.MapControllers();

// Nếu người dùng vào trang chủ mà không tìm thấy gì, trả về index.html
app.MapFallbackToFile("index.html"); 

app.Run();