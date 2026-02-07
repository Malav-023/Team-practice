#include<iostream>
#include<cmath> 

void printing(){std::cout<<"Hello World";}

double computing_sum()
{
    double sum=0;
    for(long long i=1;i<=1000000;i++)
    {
        sum+=pow(-1,i+1)/(2*i-1); 
    }
    return 4*sum; 
} 

int main()
{
    // printing();
    std::cout<<computing_sum(); 
    return 0; 
}