from locust import HttpUser, task, between
import time
import json

class MemcacheUser(HttpUser):
    wait_time = between(0.5, 2)
    host = "http://localhost:8080"

    def on_start(self):
        print("Yeni kullanıcı başlatıldı")

    @task(1)
    def get_memcache_data(self):
        start_time = time.time()
        
        with self.client.get("/", catch_response=True) as response:
            duration = time.time() - start_time
            
            if response.status_code == 200:
                try:
                    
                    json_data = response.json()
                    
                   
                    if duration > 2.0:  
                        response.failure(f"Response çok yavaş: {duration:.2f} saniye")
                    else:
                        response.success()
                        
                except json.JSONDecodeError:
                    response.failure("Geçersiz JSON yanıtı")
            else:
                response.failure(f"HTTP {response.status_code}")

    @task(2)
    def get_with_custom_header(self):
        headers = {
            'X-Custom-Test': 'locust-test',
            'Accept': 'application/json'
        }
        
        self.client.get("/", headers=headers)

    def on_stop(self):
        print("Kullanıcı durduruldu")